//############   TTN SETTINGS   #######################################################
#define device_EUI "0034CB1F3A1E17FE"
#define application_EUI "70B3D57ED0037085"
#define app_key "F10A9C97FF857E350092E410745AE7A9"

#define device_address "26011300"
#define network_session_key "1ACC5ADE5AFFCD5FED6EF37543B4604D"
#define app_session_key "6DC61FB498B1AAE158D63475F5298B64"

//############   SENSOR SETTINGS   ###################################################
//Sensor thresholds (FlashStorage overwrite with downlink) -----------
#define allow_downlink true
#define base_maximal_temperature 35
#define base_minimal_temperature 8
#define base_maximal_humidity 70
#define base_minimal_humidity 15
#define base_maximal_illumination 420
#define base_range_pressure 22000
#define base_sleepCycli 5

//############   CODE INFORMATION   ###################################################
int Version = 1;

/*- DO NOT EDIT THE CODE BELOW THIS LINE -*/
#include "defines.h"

void setup(void) {
  Serial.begin(115200);

  // LoRaWan Board hardware settings
  for (unsigned char i = 0; i < 26; i ++) { // Important, set all pins to HIGH to save power during sleeps
    pinMode(i, OUTPUT);
    digitalWrite(i, HIGH);
  }
  pinMode(38, OUTPUT); //Specifically turn pin 38 OUTPUT and HIGH to activate the Grove connectors
  digitalWrite(38, HIGH);

  //-- Starting both I2C sensors (bme and tsl) --//
  Wire.begin();
  if (!bme.begin(0x76) || !tsl.begin()) {
    Serial.println("Check wiring of the sensors!");
    while (1); //Eternal loop when disconnected
  }

  //-- Set up BME680 oversampling and filter initialization --//
  bme.setTemperatureOversampling(BME680_OS_8X); //1,2,4,8 of 16X
  bme.setHumidityOversampling(BME680_OS_8X); //1,2,4,8 of 16X
  bme.setPressureOversampling(BME680_OS_4X); //1,2,4,8 of 16X
  bme.setIIRFilterSize(BME680_FILTER_SIZE_0); //Filter size for the resistance? 0, 1, 3, 7, 15, 31, 63, 127
  /* Disabled the GAS heater to save power. */
  bme.setGasHeater(0, 0); // 0*C for 0 ms

  //-- Set up TSL2561 oversampling and gain --//
  tsl.setGain(TSL2561_GAIN_16X);
  tsl.setIntegrationTime(TSL2561_INTEGRATIONTIME_13MS);

  // Set default thresholds to the flash memory if the downlink boolean is false, so no change with downlink value
  if (downlink_occured.read() == false) {
    maximal_temperature.write(base_maximal_temperature);
    minimal_temperature.write(base_minimal_temperature);
    maximal_humidity.write(base_maximal_humidity);
    minimal_humidity.write(base_minimal_humidity);
    maximal_illumination.write(base_maximal_illumination);
    range_pressure.write(base_range_pressure);
    sleepCycli.write(base_sleepCycli);
  }

  //-- Start Lora module --//
  lora.init();

  lora.setId(device_address, device_EUI, application_EUI); //devaddr, devEUI, appEUI
  lora.setKey(network_session_key, app_session_key, app_key); //nwkskey, appskey, appkey

  lora.setDeciveMode(LWOTAA);

  lora.setDataRate(DR0, EU868);
  lora.setAdaptiveDataRate(true);

  lora.setChannel(0, 868.1);
  lora.setChannel(1, 868.3);
  lora.setChannel(2, 868.5);
  lora.setChannel(3, 867.1);
  lora.setChannel(4, 867.3);
  lora.setChannel(5, 867.5);
  lora.setChannel(6, 867.7);

  lora.setReceiceWindowFirst(869.5, DR3);
  lora.setReceiceWindowSecond(869.5, DR3);

  lora.setPower(20);

  //Ping the TTN server to JOIN
  while (!lora.setOTAAJoin(JOIN));
  digitalWrite(LED_BUILTIN, LOW); //Turn the onboard led off now that we are joined

  //RTC zetten
  rtc.begin(false);
  nextAlarmClock = rtc.getEpoch() + 60; // calculate the time of the first alarm (in one minute)
  rtc.setAlarmEpoch(nextAlarmClock);
  rtc.enableAlarm(rtc.MATCH_SS); //check the alarm based on seconds, so we wake up every minute

  delay(500);
  //perform first measurement on all sensors
  check_measurements();
  delay(500);
}

void loop(void) {
  Serial.println("########################## LOOP SEQUENCE #################################");

  if (loraSending() && allow_downlink) { //return downlink data received
    update_thresholds(); // change the threshold values in the flash if allowed
  }

  delay(20);
  Serial.println("Lora into sleep modus");
  lora.setDeviceLowPower();     // turn the LoRaWAN module into sleep mode

  for (int i = 0; i < sleepCycli.read() ; i++) { // 10 sleep cycli of 60 seconds

    doSleep(); //sleep for one minute

    if (check_measurements()) { //an alarm was raised. Cut the sleepcycle and straight to send!
      alarm = true;
      break;
    } else if (alarm) { //no alarm this time, but in case the last time was; send once more
      alarm = false;
      break;
    }
  }
} // end of loop
