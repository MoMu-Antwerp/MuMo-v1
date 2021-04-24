<?php 
error_reporting(E_ALL);
ini_set('display_errors', 'on');
include "settings.php";

$ttndtajs = file_get_contents('php://input');
//file_put_contents("test5.txt", $ttndtajs);
$data = json_decode($ttndtajs,true);

function isset_or_null($payload, $id){
    if(isset($payload[$id])){
        return "'".$payload[$id]."'";
    }
    return "NULL";
}

//Receive downlink data from the things network or direct data from gateways. Difference is in the hardware_serial id.
if(isset($data["end_device_ids"])){
	//Data from TTN.V3
    $eui = $data["end_device_ids"]["dev_eui"];
	$payload = $data["uplink_message"]["decoded_payload"]; 
    $version = isset_or_null($payload, "version");
    $ID_offsets = device_ID($eui, $version, 0);
	$ID = $ID_offsets[0];
	$offsets = json_decode($ID_offsets[1], true);
	$name = $ID_offsets[2];

	$json = array(NULL, NULL, NULL, NULL, NULL, NULL, NULL);

	if(isset($payload["battery"])){         $json[0] = round($payload["battery"], 0) 		+ $offsets[0];     }
	if(isset($payload["temperature"])){     $json[1] = round($payload["temperature"], 2) 	+ $offsets[1];     }
	if(isset($payload["humidity"])){        $json[2] = round($payload["humidity"], 2) 		+ $offsets[2];     }
	if(isset($payload["illumination"])){    $json[3] = round($payload["illumination"], 0) 	+ $offsets[3];     }
	if(isset($payload["pressure"])){        $json[4] = round($payload["pressure"], 2) 		+ $offsets[4];     }
	if(isset($payload["voc"])){             $json[5] = round($payload["voc"], 0) 			+ $offsets[5];     }
	if(isset($payload["dust"])){            $json[6] = round($payload["dust"], 3) 			+ $offsets[6];     }
	
	$frame_counter = isset_or_null($data, "counter");
	$json_encoded = json_encode($json);
	if(isset($data["uplink_message"]["rx_metadata"])){
		$connection_raw = $data["uplink_message"]["rx_metadata"];
		$connection = max(array_column($connection_raw, "rssi"));
	}else{
		$connection = NULL;
	}
	$connections = mysqli_real_escape_string($con,$connection);

    mysqli_query($con, "INSERT INTO data (device_ID, connections, frame_counter, json) VALUES ('".$ID."','".$connections."',".$frame_counter.", '".$json_encoded."')") or die ('Unable to execute query. '. mysqli_error($con));
	echo "Data saved under id: " . mysqli_insert_id($con);
	
	include("alert_check.php"); // uses $ID and json data to parse for out of bound values

}elseif(isset($data["hardware_serial"])){
    //Data from TTN
    $eui = $data["hardware_serial"];
	$payload = $data["payload_fields"]; 
    $version = isset_or_null($payload, "version");
    $ID_offsets = device_ID($eui, $version, 0);
	$ID = $ID_offsets[0];
	$offsets = json_decode($ID_offsets[1], true);
	$name = $ID_offsets[2];

	$json = array(NULL, NULL, NULL, NULL, NULL, NULL, NULL);

	if(isset($payload["battery"])){         $json[0] = round($payload["battery"], 0) 		+ $offsets[0];     }
	if(isset($payload["temperature"])){     $json[1] = round($payload["temperature"], 2) 	+ $offsets[1];     }
	if(isset($payload["humidity"])){        $json[2] = round($payload["humidity"], 2) 		+ $offsets[2];     }
	if(isset($payload["illumination"])){    $json[3] = round($payload["illumination"], 0) 	+ $offsets[3];     }
	if(isset($payload["pressure"])){        $json[4] = round($payload["pressure"], 2) 		+ $offsets[4];     }
	if(isset($payload["voc"])){             $json[5] = round($payload["voc"], 0) 			+ $offsets[5];     }
	if(isset($payload["dust"])){            $json[6] = round($payload["dust"], 3) 			+ $offsets[6];     }
	
	$frame_counter = isset_or_null($data, "counter");
	$json_encoded = json_encode($json);

	if(isset($data["metadata"]["gateways"])){
		$connection = max(array_column($data["metadata"]["gateways"], "rssi"));
	}else{
		$connection = NULL;
	}
	$connections = mysqli_real_escape_string($con,$connection);

    mysqli_query($con, "INSERT INTO data (device_ID, connections, frame_counter, json) VALUES ('".$ID."','".$connections."',".$frame_counter.", '".$json_encoded."')") or die ('Unable to execute query. '. mysqli_error($con));
	echo "Data saved under id: " . mysqli_insert_id($con);
	
	include("alert_check.php"); // uses $ID and json data to parse for out of bound values

}else{
    //Data straight from a gateway
    $data = array();
    parse_str($ttndtajs, $data);

    if(isset($data["device_ID"])){
		echo "Gateway";
		$ID = $data["device_ID"];
		$version = isset_or_null($data, "version");
		$ID_offsets = device_ID($ID, $version, 1);
		$ID = $ID_offsets[0];
		$offsets = json_decode($ID_offsets[1], true);
		$name = $ID_offsets[2];

		if(isset($data["img"])){
			//$img = $data["img"];
			$img = isset_or_null($data, "img");
			mysqli_query($con,"INSERT INTO data_images (device_ID, image) VALUES (".$ID.",".$img.")") or die ('Unable to execute query. '. mysqli_error($con));
            echo "Image saved under id: " . mysqli_insert_id($con);
		
		}else{
			$json = array(NULL, NULL, NULL, NULL, NULL, NULL, NULL);
    
			if(isset($data["battery"])){        $json[0] = round($data["battery"], 0) 		+ $offsets[0];    }
			if(isset($data["temperature"])){    $json[1] = round($data["temperature"], 2) 	+ $offsets[1];    }
			if(isset($data["humidity"])){       $json[2] = round($data["humidity"], 2) 		+ $offsets[2];    }
			if(isset($data["illumination"])){   $json[3] = round($data["illumination"], 2) 	+ $offsets[3];    }
			if(isset($data["pressure"])){       $json[4] = round($data["pressure"], 2) 		+ $offsets[4];    }
			if(isset($data["voc"])){            $json[5] = round($data["voc"], 0) 			+ $offsets[5];    }
			if(isset($data["dust"])){           $json[6] = round($data["dust"], 3) 			+ $offsets[6];    }
			//[null,18.57,50.37,0,1026.08,96457,0]

			$frame_counter = isset_or_null($data, "counter");
			$json_encoded = json_encode($json);
			mysqli_query($con, "INSERT INTO data (device_ID, frame_counter, json) VALUES ('".$ID."',".$frame_counter.",'".$json_encoded."')") or die ('Unable to execute query. '. mysqli_error($con));
			echo "Data saved under id: " . mysqli_insert_id($con);
			
			include("alert_check.php");
        }        
	}
	else{
		echo "Mumo: endpoint is active.<hr/>No data received in this request!";
	}
}

mysqli_close($con);

//Retreive data for the sensor with EUI, and update version if this changed
//If the sensor is not yet in the database, it will be added
function device_ID($eui, $version, $type){
    global $con;
    $query = "SELECT device_ID, code_version, offsets, name FROM sensoren WHERE device_EUI = '".$eui."' LIMIT 0,1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    if(is_null($row)){
		//New sensor, add to the list without name and such
		//TODO: Make this auto fill in so it adapts to new options
		$offsets = ($type)?array(null,0,0,0,0,0,0):array(0,0,0,0,0,null,null);
		$offsets = json_encode($offsets);
        mysqli_query($con, "INSERT INTO sensoren (device_EUI, code_version, type, offsets) VALUES ('".$eui."',".$version.",'".$type."','".$offsets."')") or die ('Unable to execute query22. '. mysqli_error($con));
		return array(mysqli_insert_id($con), $offsets);
    }else{
        //existing sensor, check version and return
        if($row["code_version"] != $version){ //code on the node was updated. Update in table as well
            mysqli_query($con, "UPDATE sensoren SET code_version = ".$version." WHERE device_EUI = '".$eui."'") or die ('Unable to execute query33. '. mysqli_error($con));
        }
        return array($row["device_ID"], $row["offsets"], $row["name"]);
    }
}

/* SAMPLE data:
{
	"app_id":"test_lorawan_board",
	"dev_id":"lorawan",
	"hardware_serial":"0023209FAA0B96F2",
	"port":8,
	"counter":16,
	"payload_raw":"JDMU1w==",
	"payload_fields":{
		"humidity":51,
		"illumination":215,
		"temperature":20,
		"voltage":0.72
	},
	"metadata":{
		"time":"2020-09-30T08:13:14.873342274Z",
		"frequency":867.9,
		"modulation":"LORA",
		"data_rate":"SF11BW125",
		"coding_rate":"4/5",
		"gateways":[
			{
				"gtw_id":"eui-00800000a0003ecf",
				"timestamp":2902328316,
				"time":"2020-09-30T08:13:14.789447Z",
				"channel":7,
				"rssi":-111,
				"snr":-0.2,
				"rf_chain":0,
				"latitude":51.26251,
				"longitude":4.27401,
				"altitude":45
			}
		]
	},
	"downlink_url":"https://integrations.thethingsnetwork.org/ttn-eu/api/v2/down/test_lorawan_board/mumo?key=ttn-account-v2.r-N1U0RRSa_QUsAevTMTEzQFn08qtJDbCUGbp1webug"
}

{
	"app_id":"mumo_fieldtest",
	"dev_id":"node_5",
	"hardware_serial":"0064EA2A97F45248",
	"port":8,
	"counter":125,
	"payload_raw":"AYxFIRFEBckAAA==",
	"payload_fields":{
		"battery":140,
		"humidity":69.33,
		"illumination":0,
		"pressure":1014.81,
		"temperature":17.68,
		"version":1
	},
	"metadata":{
		"time":"2020-11-12T01:12:37.452962093Z",
		"frequency":867.7,
		"modulation":"LORA",
		"data_rate":"SF12BW125",
		"coding_rate":"4/5",
		"gateways":[
			{
				"gtw_id":"winkelcentrum_ac",
				"timestamp":914118076,
				"time":"2020-11-12T01:29:50Z",
				"channel":0,
				"rssi":-121,
				"snr":-18,
				"rf_chain":0
			},
			{
				"gtw_id":"eui-60c5a8fffe76626d",
				"timestamp":3705283132,
				"time":"",
				"channel":6,
				"rssi":-108,
				"snr":-9.5,
				"rf_chain":0
			},
			{
				"gtw_id":"eui-7276ff002e062e97",
				"timestamp":3611083132,
				"time":"2020-11-12T01:12:36.404196Z",
				"antenna":1,
				"channel":19,
				"rssi":-115,
				"snr":0,
				"rf_chain":0,
				"latitude":51.17899,
				"longitude":4.41636,
				"altitude":46
			},
			{
				"gtw_id":"eui-58a0cbfffe80274b",
				"timestamp":2799016524,
				"time":"2020-11-12T01:12:37.448498964Z",
				"channel":0,
				"rssi":-43,
				"snr":11.5,
				"rf_chain":0
			}
		]
	},
	"downlink_url":"https://integrations.thethingsnetwork.org/ttn-eu/api/v2/down/mumo_fieldtest/mumo_endpoint?key=ttn-account-v2.YdTwsd0rCTRrYybJGrhxjQgOYSjcgOxBydwYVKLKRf8"
}


TTN.V3 sample data:
{
    "end_device_ids":{
       "device_id":"node-sam",
       "application_ids":{
          "application_id":"mumo-project-v1"
       },
       "dev_eui":"00CEA8CFFF53C337",
       "join_eui":"70B3D57ED0037085",
       "dev_addr":"260BCB15"
    },
    "correlation_ids":[
       "as:up:01F3347DWMN5KF9XJ6BE2VFYJ6",
       "ns:uplink:01F3347DKGDJYBM6MCAQ31CPEC",
       "pba:conn:up:01F32RKDD6S99GF2S09KTRHCXV",
       "pba:uplink:01F3347DJB1NFC7EJ0BWBNX0T6",
       "rpc:/ttn.lorawan.v3.GsNs/HandleUplink:01F3347DKGNER2MAPT73HDGK2C",
       "rpc:/ttn.lorawan.v3.NsAs/HandleUplink:01F3347DWGN458STJ96C9J4TJ9"
    ],
    "received_at":"2021-04-12T13:27:09.972931519Z",
    "uplink_message":{
       "session_key_id":"AXjGMUZc1Eo+47CMQ51ugg==",
       "f_port":8,
       "f_cnt":4,
       "frm_payload":"AVsrXRYlelMAqA==",
       "decoded_payload":{
          "battery":91,
          "humidity":43.93,
          "illumination":168,
          "pressure":1313.15,
          "temperature":22.37,
          "version":1
       },
       "rx_metadata":[
          {
             "gateway_ids":{
                "gateway_id":"packetbroker"
             },
             "packet_broker":{
                "message_id":"01F3347DJB1NFC7EJ0BWBNX0T6",
                "forwarder_net_id":"000013",
                "forwarder_tenant_id":"ttn",
                "forwarder_cluster_id":"ttn-v2-eu-4",
                "home_network_net_id":"000013",
                "home_network_tenant_id":"ttn",
                "home_network_cluster_id":"ttn-eu1",
                "hops":[
                   {
                      "received_at":"2021-04-12T13:27:09.643269571Z",
                      "sender_address":"52.169.150.138",
                      "receiver_name":"router-dataplane-f8764784f-p5gbg",
                      "receiver_agent":"pbdataplane/1.5.2 go/1.16.2 linux/amd64"
                   },
                   {
                      "received_at":"2021-04-12T13:27:09.645685289Z",
                      "sender_name":"router-dataplane-f8764784f-p5gbg",
                      "sender_address":"forwarder_uplink",
                      "receiver_name":"router-7665c7b677-c47hd",
                      "receiver_agent":"pbrouter/1.5.2 go/1.16.2 linux/amd64"
                   },
                   {
                      "received_at":"2021-04-12T13:27:09.647158108Z",
                      "sender_name":"router-7665c7b677-c47hd",
                      "sender_address":"deliver.000013_ttn_ttn-eu1.uplink",
                      "receiver_name":"router-dataplane-f8764784f-n76ql",
                      "receiver_agent":"pbdataplane/1.5.2 go/1.16.2 linux/amd64"
                   }
                ]
             },
             "time":"2021-04-12T13:27:09.578335Z",
             "rssi":-59,
             "channel_rssi":-59,
             "snr":10,
             "uplink_token":"eyJnIjoiWlhsS2FHSkhZMmxQYVVwQ1RWUkpORkl3VGs1VE1XTnBURU5LYkdKdFRXbFBhVXBDVFZSSk5GSXdUazVKYVhkcFlWaFphVTlwU201U1ZrcG9Za2RSTVZaRmRFNWtSMFpHWkVSa05VbHBkMmxrUjBadVNXcHZhVnBXVmxaWGFrcFNVMnhHU1ZwVmJ6UmFhbEUwVWtSa2FXRkZTVE5hZVVvNUxraGphamxHVEhneFpETm1VV2N3Y1ZFeU5uQTRabmN1Tmpab01GTkpUbGx2YkhNd1ZGcDBjUzVzWm5aUmVGQlJNMlV0YWxrek1HMXJaR3hhUmpWT1UyeGxiVjk1YkU1WlRHNUljVTl2YVhsTlUxUk1Xa1ZsZVhWbmJIRkhaekpSVFdwRGIxTk5PVlZ3T0ZRM1UwbERkbHA0TVV0dVgxVlBSVE5KVHpOdkxVWTVUWEY2YUZSbFFXZHFhek5HY1V3elVTMHhOa2xIZERVMFRsQnphMHR2UkRRdE0wUjFhVXh1ZVRkWlJGaHRkV0ozVUhCNWNGRm9kRWxCYjNSV1NFbGZkV05sVmpkSVdYSnJWRVZVUzAxeVRYSm1SRlF0TGs5bFIzVlNWa053WVd0RlRGbEZjVzlFY2pSaVpXYz0iLCJhIjp7ImZuaWQiOiIwMDAwMTMiLCJmdGlkIjoidHRuIiwiZmNpZCI6InR0bi12Mi1ldS00In19"
          }
       ],
       "settings":{
          "data_rate":{
             "lora":{
                "bandwidth":125000,
                "spreading_factor":7
             }
          },
          "data_rate_index":5,
          "coding_rate":"4/5",
          "frequency":"868300000"
       },
       "received_at":"2021-04-12T13:27:09.681001632Z",
       "consumed_airtime":"0.061696s",
       "locations":{
          "user":{
             "latitude":51.30663311314323,
             "longitude":4.571793079376222,
             "source":"SOURCE_REGISTRY"
          }
       }
    }
}
*/
