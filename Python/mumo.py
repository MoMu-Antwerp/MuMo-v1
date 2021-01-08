#! /usr/bin/python3
# This code is being started from /etc/xdg/lxsession/LXDE-pi/autostart. Backup options was trough /home/pi/.bashrc
import time
import board
from busio import I2C
import adafruit_bme680
import adafruit_tsl2561
import numpy as np
import RPi.GPIO as GPIO
import cv2
import requests
import base64
import schedule
import os
import subprocess
from getmac import get_mac_address

URL = "insert url to the server here!"
code_version = "2"
framecounter = 0
GPIO.setup(16, GPIO.IN, pull_up_down = GPIO.PUD_DOWN)
input = GPIO.input(16)
i2c = I2C(board.SCL, board.SDA)
bme680 = adafruit_bme680.Adafruit_BME680_I2C(i2c, 0x76, debug=False)
tsl2561 = adafruit_tsl2561.TSL2561(i2c, 0x29)
tsl2561.gain = 1 # 1 = 16x
tsl2561.integration_time = 2 # 2 = 402ms, 1 = 101ms, 0 = 13.7ms
wlan_check_flag = False
mac_address = get_mac_address(interface="wlan0")

def wlan_check():
    global wlan_check_flag
    if wlan_check_flag:
        print("long term issue, reboot")
        subprocess.call(['logger "wlan down, forcing a reboot"'], shell=True)
        wlan_check_flag = False
        subprocess.call(['sudo reboot'], shell=True)
    else:
        print("trying to recover connection")
        subprocess.call(['logger "Wlan is down, try a reconnect"'], shell=True)
        wlan_check_flag = True
        subprocess.call(['sudo /sbin/ifdown wlan0 && sleep 10 && sudo/sbin/ifup --force wlan0'], shell=True)

def take_picture():
    print("smile!")
    cap = cv2.VideoCapture(0)

    for x in range(5):
        ret, img = cap.read()
        time.sleep(1)
    cap.release()
    #save latest high res image
    cv2.imwrite("/home/pi/Desktop/code/high_res.jpg", img)

    height = 75
    scale = img.shape[0]/height
    width = int(img.shape[1] / scale)
    dim = (width, height)
    img = cv2.resize(img, dim, interpolation = cv2.INTER_AREA)
    _, img_arr = cv2.imencode('.jpg', img)
    img_bytes = img_arr.tobytes()
    img_b64 = base64.b64encode(img_bytes)
    try:
        x = requests.post(URL, data={"device_ID":mac_address, "version": code_version, "img":img_b64})
        wlan_check_flag = False   
        print(x.text)
    except requests.exceptions.ConnectionError as err:
        print ("request failed:", err)
        wlan_check()

def read_sensors():
    global framecounter
    print("starting measurements")
    counter = 0
    for x in range(55000): # this gives about 60sec measurement
        time.sleep(0.001)
        if GPIO.input(16) == 0:
            counter = counter+1
    percentage = counter / 550 # devide by the same 55000 and multiply by 100 to get %
    
    percentage = "null" if percentage is None else round(percentage,2)
    
    temp = bme680.temperature
    temp = "null" if temp is None else round(temp,2)
    humid = bme680.humidity
    humid = "null" if humid is None else round(humid,2)
    press = bme680.pressure
    press = "null" if press is None else round(press,2)
    voc = bme680.gas
    voc = "null" if voc is None else round(voc,2)
    lux = tsl2561.lux
    lux = "null" if lux is None else round(lux,2)
    framecounter = framecounter+1
    
    print("Measurements: ",temp, humid, press, voc, lux, percentage)
    try:
        x = requests.post(URL, data={"device_ID":mac_address, "version": code_version, "temperature":temp, "humidity":humid, "pressure": press, "voc": voc, "illumination": lux, "dust": percentage, "counter": framecounter})
        wlan_check_flag = False
        print(x.text)
    except requests.exceptions.ConnectionError as err:
        print ("request failed:", err)
        wlan_check()

schedule.every(5).minutes.do(read_sensors)
schedule.every().day.at("02:00").do(take_picture)
schedule.every().day.at("04:00").do(take_picture)

print("mumo logger code started! (device ID: "+mac_address+")")
time.sleep(60) # wait a minute for wifi to start up.
read_sensors()
take_picture()
while True:
    schedule.run_pending()
    time.sleep(20)

cap.release()
GPIO.cleanup()
