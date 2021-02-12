This is the Arduino code for the node.

### In the main file (mumoV1):
> ### Registration keys
>You have to insert your registration codes from TTN: (Device EUI, Application EUI, App key, Device address, Network session key en app session key

> ### Base thresholds that activate an alarm
>Also you can define base values when the device should go into alarm mode and start transmitting data every minute instead of the regular interval.
>Be carefull not setting these values too narrow. Since being in alarm mode costs a lot more energy.

>> Temperature min and max ranging from 0 to 255°C as an integer value (no decimal values)

>> Relative humidity min and max ranging from 0 to 100% as an integer value

>> Illumination maximum randing from 0 to 40.000 LUX as an integer value

>> Barometric air pressure as a difference from 101325 Pa atmosferic pressure in Pa and as an integer value

> ### base transmission frequency
>Finally you can set the base_sleepCycli. This means every how many minutes a data transmission will be send to TTN.
>Also here, a higher values means a longer life time of the device

### Downlink 
Using a downlink package these values can be changed after installation of the devices.

### Alterations
We advice not to change anything after this point in the file. But if required any other sensors can be added in the code.
* Initilisation in the defines.h file.
* Setup in the mumoV1.ino file.
* And combining the measurements into the data package in the combine_data.ino file.

## To change the location (EU, VS, AU, ...) and the channels 
* check halfway the setup section in mumoV1.ino file.

