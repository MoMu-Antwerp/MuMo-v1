boolean check_measurements() {
  boolean overshoot = false;
  Serial.println("Start measurements");

  int battery_status = measure_battery(); //Battery voltage/20

  sensors_event_t event;
  tsl.getEvent(&event);
  int lux = event.light;

  float temperature = -1.00;
  float humidity = -1.00;
  long pressure = -1;
  if (bme.performReading()) {
    temperature = bme.temperature;
    humidity = bme.humidity;
    pressure = bme.pressure;
  }

  // check if the measurments are between the threshold values, if the threshold is enabled!
  if (maximal_temperature.read()) {
    if (temperature > maximal_temperature.read()) {
      overshoot = true;
    }
  }

  if (minimal_temperature.read()) {
    if (temperature < minimal_temperature.read()) {
      overshoot = true;
    }
  }

  if (maximal_humidity.read()) {
    if (humidity > maximal_humidity.read()) {
      overshoot = true;
    }
  }

  if (minimal_humidity.read()) {
    if (humidity < minimal_humidity.read()) {
      overshoot = true;
    }
  }

  if (maximal_illumination.read()) {
    if (lux > maximal_illumination.read()) {
      overshoot = true;
    }
  }

  if (range_pressure.read()) {
    if (pressure > (atmosferic_pressure + range_pressure.read())) {
      overshoot = true;  // above the atmosferic pressure value
    }
    if (pressure < (atmosferic_pressure - range_pressure.read())) {
      overshoot = true;  // under the atmosferic pressure value
    }
  }

  combine_data(battery_status, humidity, temperature, pressure, lux); // combine all the measurments into a data package for sending

  return overshoot;
}
