void combine_data(int battery_status, float humidity, float temperature, long pressure, int lux) {
  pressure = (pressure - atmosferic_pressure) + 30000; // (pressure - atmosferic pressure) + 30000 

  //Version
  data[0] = Version;
  //voltage is within 0-255 range (0-100%)
  data[1] = battery_status;
  //split humidity into round values and decimals*100
  data[2] = (int) humidity;
  float humidity_B = (humidity - data[2]) * 100;
  data[3] = (int) humidity_B;
  //split temperature into round values and decimals*100
  data[4] = (int) temperature;
  float temperature_B = (temperature - data[4]) * 100;
  data[5] = (int) temperature_B;
  //Split pressure into two bytes since the values can go above 255
  data[6] = pressure >> 8;
  //float pressure_B = (pressure - data[6]) * 100;
  data[7] = pressure %256;
  //split lux into two bytes since the values can go above 255
  data[8] = lux >> 8;
  data[9] = lux %256;
}
