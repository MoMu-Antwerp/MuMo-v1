# MuMo Node setup
<img src="documentation/0_1.jpg" height="250" /> <img src="documentation/0_2.jpg" height="250" /> <img src="documentation/0_3.jpg" height="250" /> <img src="documentation/0_4.jpg" height="250" /> <img src="documentation/0_5.jpg" height="250" /> <img src="documentation/0_6.jpg" height="250" /> <img src="documentation/0_7.jpg" height="250" />

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

## Step 2: #Hardware - 3D Printed Parts
<img src="documentation/2_1.jpg" height="250" /> <img src="documentation/2_2.jpg" height="250" /> <img src="documentation/2_3.jpg" height="250" /> <img src="documentation/2_4.jpg" height="250" />

Check the github page for the latest STL files: [Node STL files](/Node/STL_NODE)

- ***1x*** NODE_Main_Housing
- ***1x*** NODE_Battery_Tray
- ***1x*** NODE_Backcover

### Print settings:
- PETG (preferred and more durable), but PLA also works.
- No support needed
- Infill not necessary
- 0.2 layer height
- 3 outside perimeters (for strength and durability)

## Step 3: #Hardware - Prepare the Battery Tray
<img src="documentation/3_1.jpg" height="250" /> <img src="documentation/3_2.jpg" height="250" /> <img src="documentation/3_3.jpg" height="250" /> <img src="documentation/3_4.jpg" height="250" />

### Parts:

- ***2x*** battery case (Side node: You can also use just one battery case for 3 AA batteries but the live range will be shorter!)
- ***1x*** JST 2.0 power connector (included with the Seeed LoRaWan board)
- ***1x*** 3D printed part: battery tray

### Instructions - Soldering: (Warning HOT - be careful!)

1. Solder all the red cables together
1. Solder all the black cables together.
1. Make sure that the soldering work is protected with insulation material. 
    - This can be a sleeve that you pull over the cable before soldering or insulation tape that you apply afterwards.

### Instructions - Fixation of the battery holder:

1. Glue the battery holders into the battery tray so that the cables are facing to the side with the cutout (see picture). 
    - This can be done with hot glue (preferred), double sided tape, silicone, second glue,...


## Step 4: #Hardware - Prepare LoRaWan Board
<img src="documentation/4_1.png" height="250" /> <img src="documentation/4_2.jpg" height="250" /> <img src="documentation/4_3.jpg" height="250" /> <img src="documentation/4_4.jpg" height="250" /> <img src="documentation/4_5.jpg" height="250" />

### Parts:

- ***1x*** LoRaWan board

### Instructions:

1. Before removing the led on the board, connect the board to the computer and check if the power led light up. After removing the led we have no power indication anymore.
1. In order to reduce the power consumption of the Lorawan shield we should remove two LEDs that are purely informative. The power (PWR) and the charge indication (CHG) led.
1. Be extremely careful not to damage the board during this process! Use a sharp set of pliers.
1. Locate the charging LED (CHR) and the powerLED (PWR) (see top view picture with the green rectangles)
1. Cut the soldering of the LED. The LED should come loose.
1. Remove the leds and check if the parts where removed cleanly without damaging the traces underneath.


## Step 5: #Hardware - Assembly 1: TSL2561 / BME680
<img src="documentation/5_1.jpg" height="250" /> <img src="documentation/5_2.jpg" height="250" /> <img src="documentation/5_3.jpg" height="250" /> <img src="documentation/5_4.jpg" height="250" /> <img src="documentation/5_5.jpg" height="250" /> <img src="documentation/5_6.jpg" height="250" /> <img src="documentation/5_7.jpg" height="250" /> <img src="documentation/5_8.jpg" height="250" /> <img src="documentation/5_9.jpg" height="250" />

### Parts:

- ***1x*** 3D print - "Node main body"
- ***1x*** Digital light sensor (small sensor)
- ***1x*** BME680 sensor (long sensor)
- ***2x*** Grove I2C connector cables
- ***4x*** M2x5 screws

### Instructions:

1. Connect one of the grove cables to the digital light sensor and the other to the BME680 sensor.
1. Place the sensors into the 3D print housing ("Node main body").
1. Digital light at the top left / BME680 at the top right. The connection part of the sensor is face down (not visible!). 
    - You have to bend the cables that they make a sharp turn.
1. And screw both into place with the m2x5 mm screws.

## Step 6: #Hardware - Assembly 2: Seeed LoRaWan Board 
<img src="documentation/6_1.jpg" height="250" /> <img src="documentation/6_2.jpg" height="250" /> <img src="documentation/6_3.jpg" height="250" /> <img src="documentation/6_4.jpg" height="250" /> <img src="documentation/6_5.jpg" height="250" /> <img src="documentation/6_6.jpg" height="250" /> <img src="documentation/6_7.jpg" height="250" /> <img src="documentation/6_8.jpg" height="250" /> <img src="documentation/6_9.jpg" height="250" /> <img src="documentation/6_10.jpg" height="250" />

### Parts:

- ***>*** The parts from step 5
- ***1x*** Battery tray with battery holders
- ***1x*** Seeed LoRaWan board
- ***4x*** M2x5 screws

### Instructions:

1. Insert the power cable of the battery tray into the LoRaWan board.
1. Bend the power cable over so the cables so they don't take up to much space.
1. Insert the LoRaWan board into the housing with the usb connector and power cable first.
1. Align the holes of the LoRaWan board with the mounting pins of the housing.
1. Make sure to place the LoRaWan board next to the dividing wall. (see pictures)
1. Insert the four screws into the indicated position of the board (see top view picture - Green circles)
1. When you tighten the screws make sure the reset button is properly aligned with the push button on the side of the node. (see top view picture - Blue rectangle)
1. Check if the reset button is properly working. 
    - If the button does not move or doesn't touch the reset button or the board there might be inconsistencies in the 3D print quality. Try moving the board slightly or consider breaking off the plastic printed reset button entirely to solve this. You can still reset the button trough the hole in the print.
1. Feed the antenna trough the foreseen opening in the battery support block, carefully thus not to break the antenna


## Step 7: #Hardware - Assembly 3: Connect I2C Pins
<img src="documentation/7_1.png" height="250" /> <img src="documentation/7_2.jpg" height="250" />

### Instructions:

1. Connect the Grove cables to the i2C slots on the Seeeduino. Only the two connectors closest to the edge are I2C pins and are usable for our sensors. But you may interchange both sensors connector. (see picture - blue rectangle)

## Step 8: #Hardware - Assembly 4: Cable Management - I2C Cables
<img src="documentation/8_1.jpg" height="250" />

### Instructions:

1. Behind the battery support block there is provided room to push the I2C cables down into. The fit is tight so they won't move back out.
1. Orient the cables nicely so they don't interfere with the battery tray that will be placed on top in a moment.

    > ***Comment: Leave the hardware of the node as is for now. We will setup the code first.***


## Step 9: #TTN - Sign Up / Log in
<img src="documentation/9_2.jpg" height="250" /> <img src="documentation/9_2.jpg" height="250" /> <img src="documentation/9_3.jpg" height="250" />

The things network provide a set of open tools and a global, open network to build your next IoT application at low cost, featuring maximum security and ready to scale.

> If you already have an account you can skip this step.

### Instructions:

1. Sign up at The Things Network and make an account
1. Follow the instruction on the TTN website.
1. After Sign up log in to your account
1. Go to your console. You will find it in the dropdown menu of your profile (see picture)

## Step 10: #TTN - Application Setup
<img src="documentation/10_1.jpg" height="250" /> <img src="documentation/10_2.jpg" height="250" />

> If you already have an application you can skip this step.

A application is an environment where you can store multiple node devices.

### Instructions:

1. When you are in the console click on applications (see picture 1).
1. Click on "add application"
1. You are now located in the add application window (see picture 2).
1. Make a Application ID
1. Give your Application a description
1. Set your handler registration (depending on your location)
1. When your done click "add application".

## Step 11: #TTN - Payload Formats Setup
<img src="documentation/11_1.jpg" height="250" /> <img src="documentation/11_2.jpg" height="250" />

The payload setup is important for reading your incoming data information correctly.

### Instructions:

1. In the application overview click on "Payload Formats". (see picture 1 - green rectangle)
1. Copy paste the [Payload format](/documentation/Payload_format.md) into the decoder editor. (see picture - blue rectangle)
1. Click on the save button to save your result.

## Step 12: #TTN - Add Devices
<img src="documentation/12_1.jpg" height="250" /> <img src="documentation/12_2.jpg" height="250" />

If everything goes well you are now in de Application overview where you have control over you application. 

We are now going to add a new device (node) to the application.

### Instructions:

1. Click on register device (see picture 1 - green rectangle)
1. Enter a Device ID
1. Set Device EUI to auto generated. Click on the crossing arrows on the left side.
1. When your done click on "register device".
    - The device is now created.

## Step 13: #TTN - Device Settings
<img src="documentation/13_1.jpg" height="250" /> <img src="documentation/13_2.jpg" height="250" /> <img src="documentation/13_3.jpg" height="250" /> <img src="documentation/13_4.jpg" height="250" /> <img src="documentation/13_5.jpg" height="250" />

This Step is really important to get a good connection of the LoRa setup of the devices.

### Instructions:

1. When you are in the device overview page click on "settings" (see picture 1 - green rectangle)
1. In the settings page you can give a description to your device (don't have to)
1. Set the activation mode to ABP.
1. Check off "Frame Counter checks". You will find on the bottom of the page.
1. Leave all the Device EUI, Device Address, Network Session Key, app session key to auto generation.
1. Click on the save button to save the new settings.
1. Go back to "settings" page. (see picture 3 - green rectangle)
1. Set the activation mode back to OTAA!! (see picture 4 - green rectangle)
1. Leave the App key to auto generation.
1. Click on the save button to save the new settings.(see picture 5 - green rectangle)
> This is a bit of a workaround that we needed to make this work properly. It should be possible without, but then we lost a lot of packages in TTN.

## Step 14: #Code - Arduino Code Download
<img src="documentation/14_1.png" height="250" />

Ok, so far so good. We have our node assembly, we have an account on the TTN, we created an application with the right payload format, and we made a device (OTAA) in that application. So now we only have to setup the Arduino code with the same settings information as the device we made in TTN. In the next step we will upload the code to the node.

### Instructions:

1. Download the mumoV1 directory from the Github page.
1. Download the latest version of the arduino IDE from [Arduino.cc](https://www.arduino.cc/en/software)
1. Open the arduino code file [`mumoV1.ino`](/Node/mumoV1)
    - Make sure to download the entire folder with all the files included.

## Step 15: #Code - Arduino - Device Setup With TTN
<img src="documentation/15_1.jpg" height="250" /> <img src="documentation/15_2.jpg" height="250" />

### Instructions:

1. Open thethingsnetwork (TTN), go to your device overview where you will find all the settings information of the device. We are going to use this for setup the arduino code.
1. In the arduino code go to "mumoV1.h" tab.

***Setup node ID:***

1. Copy the device_EUI from the TTN and paste it in the arduino code (see purple arrow).
1. Copy the application_EUI from the TTN and paste it in the arduino code (see blue arrow).
1. Copy the app_key from the TTN and paste it in the arduino code (see green arrow).If the network_session_key is not visible click on the "eye" symbol (see the green circle).
1. Copy the device_adress from the TTN and paste it in the arduino code (see yellow arrow).
1. Copy the network_session_key from the TTN and paste it in the arduino code (see orange arrow). If the network_session_key is not visible click on the "eye" symbol (see the orange circle).
1. Copy the app_session_key from the TTN and paste it in the arduino code (see red arrow). If the app_session_key is not visible click on the "eye" symbol (see the red circle).

## Step 16: #Code - Arduino - Install RTC and Adafruit Library
<img src="documentation/16_1.png" height="250" /> <img src="documentation/16_2.png" height="250" /> <img src="documentation/16_3.png" height="250" /> <img src="documentation/16_4.png" height="250" /> <img src="documentation/16_5.png" height="250" />

The components we use need libraries for them to work.

1. In you arduino interface click on Sketch > Include Library > Manage Libraries...

***The library management window will pop up.***
1. In the search bar type: rtczero
    - Install the latest version of the first library
1. In the search bar type: adafruit BME680 (For the BME680 sensor)
    - Install the latest version of the first library
1. In the search bar type: adafruit TSL2561 (For the TSL2561sensor)
    - Install the latest version of the first library.
1. In the search bar type: flashstorage ATSAM
    - Install the latest version of the first library.

## Step 17: #Code - Arduino - Seeeduino LoRaWAN Library Install
<img src="documentation/17_1.jpg" height="250" /> <img src="documentation/17_2.jpg" height="250" />

Also the board we use (Lorawan) needs to be added to Arduino to be able to programm it.

### Instructions:

1. In you arduino interface click on File > Preferences, and copy the url (underneath) to "Additional Boards Manager URLs" (see picture - red rectangle).
    `https://files.seeedstudio.com/arduino/package_seeeduino_boards_index.json`
1. Click on "ok".
1. Back at the arduino interface click on Tools > Board > Board Manager.
1. In the search bar type "lorawan".
1. You will see the library of Seeed LoRaWan board. (see picture - green rectangle).
1. Click on "install" and wait until it's done installing all dependancies.

## Step 18: #Code - Arduino - Board Selection / COM Port
<img src="documentation/18_1.png" height="250" />

### Instructions:

1. Connect the LoRaWAN board with a micro usb cable to your computer.
1. In you arduino interface click on Tools > Board and select the "Seeeduino LoRaWAN" board. (see picture)
1. Select in the same menu the correct COM port.

## Step 19: #Code - Arduino - Upload the Code to the Board
<img src="documentation/19_1.jpg" height="250" />

Now that we have our code ready, it's time to put the code on to the LoRaWAN board!

### Instructions:

1. Make sure your LoRaWAN board is still connected to your pc.
1. Double click the reset button on the side node. You will see that the led are flickering. This means that the device is in bootloader mode.
1. Because of the bootloader modus we might have to select a new COM port. This is done exacly the same as in Step 18.
1. Click on the upload button. It's the button with the arrow pointing to the right. (See picture - red circle).
1. After a while you should get an "upload done" notification in the bottom right corner.

## Step 20: #Code - Arduino - Test the Code!
<img src="documentation/20_1.jpg" height="250" /> <img src="documentation/20_1.jpg" height="250" />

### Instructions:

1. On the device overview of TTN click on "Data". There you will find all the incoming data that specific node device. (see picture - red rectangle)
1. To test the data transmission, press the reset button on the side of the node device to send signal.
1. If the LoRa signal is received by a gateway you will see the incoming data in you application data of the device on the TTN. (It might take a minute to see the result)
1. If you don't see incoming data try to push the rest button on the side of the node device again to re-send the signal again.
    - If this is not helping, you back to Step 18 and try to upload the code again.
    - You can open the terminal window in Arduino to check if no errors are showing up like bad connections to the sensors or obvious errors that the code can recognize.
1. Congrats you have now a working LoRa Node device!
1. Remove the USB cable from the lorawan board.
1. Push one last time on the rest button on the side of the node device.
    - This resets the led's so they don't stay lit.

## Step 21: #Hardware - Assembly 5: Insert Battery Tray
<img src="documentation/21_1.jpg" height="250" /> <img src="documentation/21_2.jpg" height="250" /> <img src="documentation/21_3.jpg" height="250" /> <img src="documentation/21_4.jpg" height="250" /> <img src="documentation/21_5.jpg" height="250" />

### Parts:

- ***>*** The assembly stack from step 6: Battery tray

### Instructions:

1. Insert the battery tray into the housing under an angle. Make sure you first position the power cable in the right direction. (see picture)
1. First position the tray on to the support block wall where the cables are stuffed behind.
1. Push the tray down until you hear a "snap click" sound.
1. Check the corner that the tray has a nice fit into the main housing. (see picture 2 / 3 - red circles) // weg
1. Insert the power cable on top of the I2C connection cables. 
    - If needed: Push it down with something blunt. 
    - ***Be careful not to damage the cables.***

## Step 22: #Hardware - Assembly 6: Insert Batteries
### Parts:

- ***6x*** AA batteries
### Instructions:

1. Insert the batteries into the battery holders.
1. make sure the batteries have decent contact.
> ***Side node: Check the battery orientation of the battery holder. This may vary per tray type.***


## Step 23: #Hardware - Assembly 7: Back Cover
<img src="documentation/23_1.jpg" height="250" />

### Parts:

- ***1x*** 3D print - Back cover node
### Instructions:

1. Insert the back cover lips into the lip grove of the main body housing under a slide angle.
1. Push on the side of the housing and make sure it is is the right position.
1. If the lips are not fitting because of print issues try to grind some of the surface until it fits. 
    - Check that the back cover is completely flat on the housing and that there are no open seams.
1. Insert the M3x12mm screws and tighten.

## Step 24: #Hardware - Attachment of the Device
<img src="documentation/24_1.jpg" height="250" /> <img src="documentation/24_2.jpg" height="250" /> <img src="documentation/24_3.jpg" height="250" /> <img src="documentation/24_4.jpg" height="250" /> <img src="documentation/24_5.jpg" height="250" />

There are several ways to attach the device:
- Screw slide lock groove on the side.
- Screw slide lock groove on the back.
- Tiewrap groves on the side / top and back.
- And the backcover of the node is also provided with a hook.
