# MuMo Gateway setup
<img src="documentation/0_1.jpg" height="250" /> <img src="documentation/0_2.jpg"  height="250" /> <img src="documentation/0_3.jpg"  height="250" /> <img src="documentation/0_4.jpg"  height="250" />

### Required Tools:
- 3D printer with filament
- Solder iron / solder
- Small cutting plier
- Hot glue gun (or other fixation tools)
- Small screwdriver

## Step 1: #Hardware - Ordering Parts
<img src="documentation/1_1.png" height="250" />

See the shopping list an up-to-date overview:
- [Shopping list for the project.](/Shopping_list.md)

In the construction of the gateway you have a few options in the features you like to add:
- Either an active or passive gateway (with or without sensors on the gateway).
- Lora gateway or just an active sensor (with or without lorawan concentrator shield).
- With or without camera module for bug tracking or other camera applications.

> In the instructions below we describe the most commong optional parts (without camera and without sensors). 
> But note that you can easily leave out the lorawan concentrator as well if you already have gateways on site.

***Be carefull*** about the screws nearby the Raspberry Pi. Don't overtighten the screws and if you notice it being to close to the electronics we advice you to shim the parts a bit or to use a smaller screw.

## Step 2: #Hardware - 3D Printed Parts
Check the github page for the latest STL files:
[Gateway STL files](/Gateway/STL_GATEWAY)
- ***1x*** GATEWAY_Main_Housing
- ***1x*** GATEWAY_Backcover
- ***1x*** Sensor_extension
- ***1x*** Sensor_Housing
- ***1x*** Sensor_Backcover
- ***1x*** Camera_Housing
- ***1x*** Camera_Backcover

### Material and print settings:
- ***PETG*** preffered (for durability against the heat of the gateway)
- No supports needed
- Infill not necessary
- 0.2 layer height
- 3 outside perimeters (for strength and durability)

## Step 3: #Software - Prepare SD Card Raspberry Pi
<img src="documentation/3_1.jpg" height="250" /> <img src="documentation/3_2.jpg" height="250" />

### Parts:

- ***1x*** Raspberry Pi
- ***1x*** Micro SD card.

### Instructions:

1. Make sure the SD card is flashed with the Raspberry Pi operation system (Raspberry Pi OS(32-bit) with desktop). 
1. Insert your micro SD card into the Raspberry Pi.

> Follow the link below to find more detailed instructions how to flash and prepare your micro SD card. [Raspberry installation instructions](https://www.raspberrypi.org/documentation/installation/installing-images/)


## Step 4: #Hardware - Prepare the Air Dust Sensor (optional)

<img src="documentation/4_1.jpg" height="250" /> <img src="documentation/4_2.jpg" height="250" /> <img src="documentation/4_3.jpg" height="250" /> <img src="documentation/4_4.jpg" height="250" /> <img src="documentation/4_5.jpg" height="250" /> <img src="documentation/4_6.jpg" height="250" /> <img src="documentation/4_7.jpg" height="250" /> <img src="documentation/4_8.jpg" height="250" /> <img src="documentation/4_9.jpg" height="250" /> <img src="documentation/4_10.jpg" height="250" /> <img src="documentation/4_11.jpg" height="250" /> <img src="documentation/4_12.jpg" height="250" /> <img src="documentation/4_13.jpg" height="250" /> 

Since the air dust sensor works on 5V supply and the raspberry pi only works on 3.3V we need to include a voltage devider in the cable using two resistors.

### Parts:

- ***1x*** Seeed air dust sensor
- ***2x*** resistor (3.3 KΩ) (orange, orange, red)
- ***1x*** Grove hat
- ***2x*** shrink sleeves

### Instructions:
1. Cut the red wire up to the connector.
1. Cut the yellow wire at a distance of 3 cm from the connector.
1. Cut the black wire at a distance of 2 cm from the connector.
1. Strip the end of each wire.
1. Put a small shrink sleeve over the yellow cable.
1. Put a large shrink sleeve over the yellow and black cable.
1. Solder the two resistors in series with the yellow cable of the connector in between.
1. Solder the other yellow cable on the side of the sensor to one of the resistors.
1. Slide the small sleeve over the solder connection of the yellow wire with one resistor end still exposed and heat shrink the small sleeve.
1. Solder the black wires back together with the still exposed resistor leads in between.
1. Slide the large sleeve over the solder connection and the small sleeve and heat shrink the large sleeve.
1. Solder the red cable to the 5V pins (pin 2 and 4) on the Grove hat board (see the top view picture).

## Step 5: #Hardware - Mounting the Spacers (optional)
<img src="documentation/5_1.jpg" height="250" /> <img src="documentation/5_2.jpg" height="250" /> <img src="documentation/5_3.jpg" height="250" /> <img src="documentation/5_4.jpg" height="250" />

### Parts:

- ***1x*** Grove hat board
- ***1x*** Seeed air dust sensor
- ***4x*** female-male spacers
- ***4x*** female-female spacers
- ***4x*** nut

### Instructions:
1. Mount the female-male spacers through the mounting holes of the grove hat board
1. Screw the nuts on the female-male spacers and tighten it. (to provide extra space for the cables to bend)
1. Screw the female-female spacers on top of the nuts and tighten everything.
1. Lay the red 5V cable of the airdust sensor along the inside of the spacer (see last picture).

## Step 6: #Hardware - Connecting Camera Cable / Dust Sensor / I2C (optional)
<img src="documentation/6_1.jpg" height="250" /> <img src="documentation/6_2.jpg" height="250" /> <img src="documentation/6_3.jpg" height="250" /> <img src="documentation/6_4.jpg" height="250" /> <img src="documentation/6_5.jpg" height="250" /> <img src="documentation/6_6.jpg" height="250" /> <img src="documentation/6_7.jpg" height="250" /> <img src="documentation/6_8.jpg" height="250" /> <img src="documentation/6_9.jpg" height="250" /> <img src="documentation/6_10.jpg" height="250" /> <img src="documentation/6_11.jpg" height="250" /> <img src="documentation/6_12.jpg" height="250" />

### Parts:

- ***>*** The assembly stack from step 6
- ***1x*** Raspberry PiModel 3 B+
- ***1x*** Camera cable
- ***2x*** grove connection cables
- ***1x*** Long M2.5 screw from the Grove hat bag

### Instructions:

__Camera cable:__
1. Lift the latch of the cable connecter on the Raspberry Pi (see picture one - red rectangle). Be careful, fragile!
1. Insert the camera cable in the connector of the Raspberry Pi with the blue side facing the usb plugs.
1. When the cable is in the right place. Push the latch back into place so the cable connection is secured.
1. Feed the camera cable trough the provided hole into the grove board. (see picture of the grove board top view - red rectangle)
1. Align the board with the pin connections on the side.
1. Push it al the way down to make a stack.
1. To secure the stack, mount the screw in the hole next to the audio connection of the raspberry pi. (see picture top view)
1. The first stack is complete!

__Air dust sensor:__
1. Connect the connector of the air dust sensor to pin D16 of the Grove hat board. ( see picture of the grove board top view - purple rectangle )

__I2C connectors:__
1. Connect the two grove connection cables to the I2C connectors of the the Grove hat board. Preferably use the connectors that are close to the camera cable. This makes it easier to use the HDMI port afterwards. ( see picture of the grove board top view - blue rectangle )

## Step 7: #Hardware - Building the Stack Into the Housing
<img src="documentation/7_1.jpg" height="250" /> <img src="documentation/7_2.jpg" height="250" /> <img src="documentation/7_3.jpg" height="250" /> <img src="documentation/7_4.jpg" height="250" /> <img src="documentation/7_5.jpg" height="250" /> <img src="documentation/7_6.jpg" height="250" /> <img src="documentation/7_7.jpg" height="250" />

### Parts:

- ***>*** The assembly stack from step 6
- ***1x*** Gateway_body 3D print
- ***1x*** M3 x 12
- ***3x*** Long M2.5 screws from the Grove hat bag

### Instructions:

1. Check if the micro SD card is inserted into the Raspberry Pi.
1. Insert the air dust sensor in the 3D print housing and secure it with the M3 screw.
1. Before we insert the stack. Guide the camera cable and the two I2C grove connection cables through the bottom slot in the housing.
1. Insert the Pi stack into the housing.
1. Push the cables down on the side so they don't get in the way.
1. Make sure that no wires are in front of the Micro USB and the HDMI connection.
1. Secure the stack with three M2.5 screws through the big holes in the front.


## Step 8: #Hardware - Dragino LoRa Shield
<img src="documentation/8_1.jpg" height="250" /> <img src="documentation/8_2.jpg" height="250" /> <img src="documentation/8_3.jpg" height="250" />

### Parts:

- ***>*** The assembly from step 7
- ***1x*** Dragino LoRa shield
- ***4x*** Short M2.5 screws from the Lora Concentrator bag

### Instructions:

1. Pre install the antenna to the Dragino LoRa shield. (don't fully tighten yet!)
1. Insert the Dragino LoRa shield on top of the grove hat board. Align the pins and push it all the way down.
1. Secure the board with the four M2.5 screws.

## Step 9: #Hardware - Backcover
<img src="documentation/9_1.jpg" height="250" /> <img src="documentation/9_2.jpg" height="250" /> <img src="documentation/9_3.jpg" height="250" /> <img src="documentation/9_4.jpg" height="250" />

### Parts:

- ***>*** The assembly from step 8
- ***1x*** Gateway_backcover
- ***2x*** M3 x 12 screws

### Instructions:

1. Slide the inserts of the backcover into the housing and push it down.
1. Fixated the backcover with two M3 screws.

## Step 10: #Hardware - Setup LoRa Gatway
<img src="documentation/10_1.jpg" height="250" />

### Parts:

- ***>*** The assembly from step 9
- ***1x*** Peripherals: screen (HDMI) / keyboard / mouse
- ***1x*** Micro usb power supply (5.1V 2.5A)
### Instructions:

1. Connect the Raspberry to a screen with a HDMI cable.
1. Connect a mouse, keyboard to the USB connector.
1. Plug in the power usb cable to the Raspberry Pi last. 
    - It should start booting up now.
    
In these instructions we chose to use a display connected at all times during setup. You can also choose to connect trough SSH, but then you do not get to see all the visual information that often helps you along the way. 

## Step 11: #Software - Setup LoRa Gatway - First Start Up Raspberry Pi
<img src="documentation/11_1.png" height="150" /> <img src="documentation/11_2.png" height="150" /> <img src="documentation/11_3.png" height="150" /> <img src="documentation/11_4.png" height="150" /> <img src="documentation/11_5.png" height="150" /> <img src="documentation/11_6.png" height="150" /> <img src="documentation/11_7.png" height="150" /> <img src="documentation/11_8.png" height="150" /> <img src="documentation/11_9.png" height="150" />

### Instructions:

1. You will see the setup screen. Follow the setup screen instructions.
1. Choose your county / network / keyboard setting
1. At the end it will search for updates and install them.
    - Please be patient, this can take a few minutes.

## Step 12: #Software - Setup LoRa Gatway - Get Ether Adress for TTN
<img src="documentation/12_1.png" height="150" />

### Instructions:

1. Open a terminal on the Raspberry Pi.
1. Type in > `ifconfig wlan0:`
1. You can see the ether address of the Pi. (ex: b5:23:eb:fc:55:d4)
1. ***Write this down*** because you will need it when setting up the gateway in TTN.

> For more detail setup information about the Dragino PG1301, check their user manual (page 7): [Dragino PG1301 user manual](http://www.dragino.com/downloads/downloads/LoRa_Gateway/PG1301/PG1301_UserManual_v1.0.2.pdf)

## Step 13: #TTN - Sign Up / Log in
<img src="documentation/13_1.png" height="250" />

The things network provide a set of open tools and a global, open network to build your next IoT application at low cost, featuring maximum security and ready to scale.
[https://www.thethingsnetwork.org/](https://www.thethingsnetwork.org/)
> If you already have an account you can skip this step.

### Instructions:

1. Sign up at The Things Network and make an account
1. Follow the instruction on the TTN website.
1. After Sign up log in to your account
1. Go to your console. You will find it in the dropdown menu of your profile (see picture)

## Step 14: #TTN - Create a Gatway on the TTN
<img src="documentation/14_1.jpg" height="150" /> <img src="documentation/14_2.jpg" height="150" /> <img src="documentation/14_3.jpg" height="150" />

### Instructions:

1. In the console on TTN, click on Gateway.
1. Click on register gateway in the upper right corner to at a new gateway device. (see picture - red square)
1. Check the box of "I'm using the legacy packet forwarder". (see picture - green square)
1. Fill in the gateway EUI by using the ether address from the Pi. Convert your address like this example `b5:23:eb:fc:55:d4` => `B523EBFC55D4FFFF` (see picture - green rectangle) The "FFFF" gets added to make it a 8 byte unique EUI.
1. Choose your Frequency plan ( ex: Europe - 868MHz for Europe)
1. Choose your router ( ex: ttn-router-eu for Europe)
1. Point your location on the map. (optional)
1. Check the right box, indoor or outdoor.
1. On the bottom of the page click on the button Register Gateway

## Step 15: #Software - Setup LoRa Gatway - Interface Options
<img src="documentation/15_1.png" height="250" /> <img src="documentation/15_2.png" height="250" /> <img src="documentation/15_3.png" height="250" />

### Instructions:

In the terminal type in > `sudo raspi-config`
- Select Interface options
    - Select and enable SPI
    - Select and enable Camera
    - Select and enable I2C


## Step 16: #Software - Setup LoRa Gatway - Download and Install LoRaWAN Packet Forwarder Enable SPI
<img src="documentation/16_1.png" height="250" /> <img src="documentation/16_2.png" height="250" />

### Instructions:

1. In the terminal type in > 
`wget http://www.dragino.com/downloads/downloads/LoRa_Gateway/PG1301/software/lorapktfwd.deb`
1. This will download the packet forwarder from Dragino Server to RPI.
1. In the terminal type in > `sudo dpkg -i lorapktfwd.deb`


## Step 17: #Software - Setup LoRa Gatway - Config Gateway ID, Frequency Band and Server Address
<img src="documentation/17_1.png" height="250" /> <img src="documentation/17_2.png" height="250" />

### Instructions:

1. After installation, go to `/etc/lora-gateway/` and open local_conf.json
1. In between the curly brackets add this section below:
    ```
    "gateway_ID": "B523EBFC55D4FFFF",
    "server_address": "router.eu.thethings.network",
    "serv_port_up": 1700,
    "serv_port_down": 1700
    ```
    - Make sure to place the correct structure with , after each line and no , at the last line. 
    - " " around all text values and none around the numbers.
1. Change the gateway_ID to the gateway_ID you used to setup the gateway in the TTN. (with the "FFFF")
1. Save the document.


## Step 18: #Software - Setup LoRa Gatway - Start the LoRa Network
<img src="documentation/18_1.png" height="250" /> <img src="documentation/18_2.jpg" height="250" />

### Instructions:

1. In the terminal type >
    ```
    sudo systemctl stop lorapktfwd
    sudo systemctl start lorapktfwd
    sudo systemctl enable lorapktfwd
    ```
1. This restarts the package forwarder and makes sure the forwarder starts with Raspberry Pi. 
    - Now your LoRa gateway is active.
1. You should see the status update to "connected" within a few minutes on TTN.

## Step 19: #Software - Setup Gateway - Sensor / Camera - Install (optional)
<img src="documentation/19_1.png" height="150" /> <img src="documentation/19_2.png" height="150" /> <img src="documentation/19_3.png" height="150" /> <img src="documentation/19_4.png" height="150" /> <img src="documentation/19_5.png" height="150" /> <img src="documentation/19_6.png" height="150" />

### Instructions:

1. Check if you have python 3 on your Raspberry Pi. In the terminal type => python3
1. If you don't have python 3, follow this install instructions:
1. type => `sudo apt update`
1. type => `sudo apt upgrade`
1. type => `sudo apt install python3 idle3`
1. Now you should have python 3. Please check again with the 1. first step.
1. Activate camera / I2C / SPI: _(you might have done this already in the LoRa setup)_
1. In the terminal type => `sudo raspi-config`
    - Go to Interfacing Options.
        - Enable camera
        - Enable I2C
        - Enable SPI
1. Install following libraries: (type these commands in the terminal)
    ```
    sudo apt-get update
    sudo apt-get upgrade
    sudo apt-get install libatlas-base-dev

    pip3 install numpy
    pip3 install opencv-python
    pip3 install scikit-image
    pip3 install schedule
    pip3 install getmac
    pip3 install adafruit-circuitpython-bme680
    pip3 install adafruit-circuitpython-tsl2561
    pip3 install RPI.GPIO
    ```

## Step 20: #Software - Setup Gateway - Sensor / Camera - Script Run (optional)
<img src="documentation/20_1.png" height="150" /> <img src="documentation/20_2.png" height="150" /> <img src="documentation/20_3.png" height="150" />

### Focus setup:
1. Before running the final code we have to focus our camera unit. 
1. In a command line window type:
    ```
    from picamera import PiCamera
    from time import sleep

    camera = PiCamera()

    camera.start_preview()
    sleep(20)
    camera.stop_preview()
    ```
1. This provides you a live preview of the camera. 
1. Focus the camera by rotating it's lens.
1. After 20sec the preview stops, but you can re-run the code as much as needed.

### Instructions:

1. Download the python script `mumo.py` from github: [Gateway python code](Gateway/Python/mumo.py)
1. Place the code on your desktop.
1. Open a terminal and type > `sudo nano /etc/xdg/lxsession/LXDE-pi/autostart`
1. Copy this line on to the bottom off the file > `@lxterminal -e python3 /home/pi/Desktop/mumo.py`
1. Save the file and close it.
1. Now the script will automatically start at restart.
1. Open the code.
1. ***Change to your URL endpoint.*** 
    - This is where to send the data on your backend server.
    - This data goes straight to the dashboard and not trough TTN.


## Step 21: #Hardware - Sensor Extension (optional)
<img src="documentation/21_1.jpg" height="250" /> <img src="documentation/21_2.jpg" height="250" /> <img src="documentation/21_3.jpg" height="250" /> <img src="documentation/21_4.jpg" height="250" /> <img src="documentation/21_5.jpg" height="250" /> <img src="documentation/21_6.jpg" height="250" /> <img src="documentation/21_7.jpg" height="250" /> <img src="documentation/21_8.jpg" height="250" /> <img src="documentation/21_9.jpg" height="250" /> <img src="documentation/21_10.jpg" height="250" />

### Parts:

- ***>*** The assembly from step 9
- ***1x*** Sensor_body
- ***1x*** Sensor_cap
- ***1x*** Digital light sensor (small sensor)
- ***1x*** BME680 sensor (long sensor)
- ***4x*** M2 x 5 screws
- ***4x*** M3 x 12 screws
### Instructions:

1. Insert the two I2C grove connection cables through the hole of the sensor_cap.
1. Connect the BME680 sensor and the digital light sensor to the I2C grove connection cable.
1. Insert the BME680 sensor and the digital light sensor into the sensor_body part and secure it with four M2x5 screws. You will have to bend the cable to fit the sensors into place, so be careful!
1. Slide the sensor_cap on top of the sensor body to close it.
1. Fixated the cap to the body with two M3 screws.
1. Attach the sensor add-on assembly to the front of the gateway with two M3 screws. (see picture - Red circle)
1. The grove cables are probably too long. Push them inside the sensor housing.

## Step 22: #Hardware - Camera Extension (optional)
<img src="documentation/22_1.jpg" height="250" /> <img src="documentation/22_2.jpg" height="250" /> <img src="documentation/22_3.jpg" height="250" /> <img src="documentation/22_4.jpg" height="250" /> <img src="documentation/22_5.jpg" height="250" /> <img src="documentation/22_6.jpg" height="250" /> <img src="documentation/22_7.jpg" height="250" /> <img src="documentation/22_8.jpg" height="250" /> <img src="documentation/22_9.jpg" height="250" /> <img src="documentation/22_10.jpg" height="250" /> <img src="documentation/22_11.jpg" height="250" /> <img src="documentation/22_12.jpg" height="250" /> <img src="documentation/22_13.jpg" height="250" />

### Parts:

- ***>*** The assembly from step 10
- ***1x*** Camera module (with M2.5 screws)
- ***1x*** Camera_body
- ***1x*** Camera_cap
- ***4x*** M3 x 12 screws
### Instructions:

1. Place the camera and one light attachment into the camera_cap housing and secure it with the four M2.5 screws from the camera module.
1. To insert the camera cable we must lift the black plastic holder from the connection.
1. Insert the camera cable with the blue surface facing the camera. (see pictures)
1. Slide the camera_body on top of the assembly
1. Fixated the camera_cap with two M3 screws to the camera_body.
1. Mount the camera add on assembly to the bottom off the gateway housing with two M3 screws (see picture - Red circle)
Push the protruding cable into the housing.

## Step 23: #Hardware - Bug Trap Extension (optional)
<img src="documentation/23_1.jpg" height="250" /> <img src="documentation/23_2.jpg" height="250" /> <img src="documentation/23_3.jpg" height="250" /> <img src="documentation/23_4.jpg" height="250" /> <img src="documentation/23_5.jpg" height="250" /> <img src="documentation/23_6.jpg" height="250" /> <img src="documentation/23_7.jpg" height="250" /> <img src="documentation/23_8.jpg" height="250" /> <img src="documentation/23_9.jpg" height="250" /> <img src="documentation/23_10.jpg" height="250" /> 

### Parts:

- ***>*** The assembly from step 11
- ***1x*** Trap_Frame
- ***1x*** bug trap paper - sticky paper
- ***2x*** M3 x 12 screws
### Instructions:

1. Place the Trap_Frame part on top of the camera housing. The trap has some space for the power usb cable of the gateway, therefore check the pictures for the correct orientation.
1. Fixate with two M3 screws on the left and right side of the camera housing.
1. Insert your (60 x 75) mm bug paper into the slot of the trap. There are two slots, in the front and back direction. It depends how you will position the gateway.
1. The power usb cable can be weaved between the open structure of the trap part.

## Step 24: #Hardware - Mounting the Gateway
### The gateway is provided with many options to mount the gateway.
<img src="documentation/24_1.jpg" height="250" /> <img src="documentation/24_2.jpg" height="250" /> <img src="documentation/24_3.jpg" height="250" /> <img src="documentation/24_4.jpg" height="250" /> <img src="documentation/24_5.jpg" height="250" /> <img src="documentation/24_6.jpg" height="250" /> <img src="documentation/24_7.jpg" height="250" />

We have two screw slots on which the gateway can be hung.

And also have cable ties grooves, so you can easily attach the gateway to anything.


## Step 25: #Hardware - Differed Orientations
The gateway is modular so that the sensors and camera can be mounted in different orientations. You can also create your own components and add them to the setup.
