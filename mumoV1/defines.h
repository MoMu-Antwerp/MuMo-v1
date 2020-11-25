#define atmosferic_pressure 101325 //Pa
#include <Wire.h>
#include <RTCZero.h>
#include <LoRaWan.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME680.h>
#include <Adafruit_TSL2561_U.h>
#include <FlashStorage.h> /* https://github.com/cmaglie/FlashStorage */

// Sensors -----------------------------------------------------------
Adafruit_TSL2561_Unified tsl = Adafruit_TSL2561_Unified(0x29, 12345);
Adafruit_BME680 bme;

// Prepare Flash storage slots
FlashStorage(downlink_occured, boolean);
FlashStorage(maximal_temperature, float);
FlashStorage(minimal_temperature, float);
FlashStorage(maximal_humidity, float);
FlashStorage(minimal_humidity, float);
FlashStorage(maximal_illumination, long);
FlashStorage(range_pressure, long);
FlashStorage(sleepCycli, int);

// RTC --------------------------------------------------------------
RTCZero rtc;
long nextAlarmClock;
#define sleepcycle 60 //wake up every 60 sec

// Data var --------------------------------------------------------------
unsigned char data[10];
unsigned char downlinkData[10];
char buffer[256];
