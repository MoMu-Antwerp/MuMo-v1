// NODE_1_Mumo_fieldtest
//###################################################################
#define device_EUI "0023209FAA0B96F2"
#define application_EUI "70B3D57ED002B319"
#define app_key "E1BF81CCC0C97FBD1188F5EFAA15497D"

#define device_address "26011A0B"
#define network_session_key "4432B3AA248FFFBE25A1B43C147C8094"
#define app_session_key "747657B65D8E26337769F223F2A09FCB"

//Sensor thresholds (FlashStorage overwrite with downlink -----------
#define base_maximal_temperature 35
#define base_minimal_temperature 8
#define base_maximal_humidity 70
#define base_minimal_humidity 15
#define base_maximal_illumination 420
#define base_range_pressure 22000
#define base_sleepCycli 10

//Code information ---------------------------------------------------------
int Version = 2;


/*- DO NOT EDIT THE CODE BELOW THIS LINE -*/
#include "defines.h"

void setup(void) {
  Serial.begin(115200);

  // LoRaWan Board hardware settings
  for (unsigned char i = 0; i < 26; i ++) {    // important, set all pins to HIGH to save power during sleeps
    pinMode(i, OUTPUT);
    digitalWrite(i, HIGH);
  }
  digitalWrite(LED_BUILTIN, LOW); //but turn the led back off
  pinMode(38, OUTPUT); //Specifically turn pin 38 HIGH to activate the Grove connectors
  digitalWrite(38, HIGH);

  //-- Starting I2C sensors --//
  Wire.begin();
  if (!bme.begin(0x76) || !tsl.begin()) {
    Serial.println("Check wiring of the sensors!");
    while (1);
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

  while(!lora.setOTAAJoin(JOIN));

  //-- Starting the internal RTC to help with long sleeps --//
  rtc.begin(false); //false to use relative time to this moment
  nextAlarmClock = rtc.getEpoch() + sleepcycle; // In this case, the ref. point for the alarm sequence

  delay(1000);
  //perform first measurement on all sensors
  check_measurements();
  delay(500);

}

void loop(void) {
  Serial.println("########################## NEW LOOP SEQUENCE #################################");

  if (loraSending()) { //return downlink data received
    update_thresholds(); // change the threshold values in the flash
  }

  delay(20);
  Serial.println("Lora into sleep modus");
  lora.setDeviceLowPower();     // turn the LoRaWAN module into sleep mode

  Serial.println("SleepMode!zzzzz ... sleep tight ...");

  for (int i = 0; i < sleepCycli.read() ; i++) { // 10 sleep cycli of 60 seconds
    doSleep(); // deep sleep for 60 secs (+ 3 secs transmission time + 5 secs timeout = 60 secs period)
    //delay(60000);  // 60 second "sleep"
    if (check_measurements()) {
      //an alarm was raised. Cut the sleepcycle and straight to send!
      break;
    }
  }

} // end of loop
