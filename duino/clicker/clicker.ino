/* ======================================================================
 *
 * Green Electronic Technology: Efficient Management of Energy through
 * Responsive and Attentive Live-Data Sourcing (GET:EMERALDS)
 *
 * Filename: clicker.ino
 *
 * Written by:
 *   - Arbyn Acosta <arbyn.acosta@ieee.org>
 *
 * Copyright (c) 2016-2017
 * GET:EMERALDS
 * All Rights Reserved
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * =================================================================== */

#include <EMERALDS_Base.h>

#include <Adafruit_PWMServoDriver.h>

// debugging mode
#define DEBUG_MODE

// networking ids
#define NETWORKID  EMERALDS_NET_ID
#define NODEID     CLICKER_NET_ID
#define RECEIVER   MARC_NET_ID

#define SERVOMIN  150 // this is the 'minimum' pulse length count (out of 4096)
#define SERVOMAX  450 // this is the 'maximum' pulse length count (out of 4096)

// create radio instance
RFM69 node = RFM69(FEATHER_RFM69_CS, FEATHER_RFM69_IRQ, IS_RFM69HCW, FEATHER_RFM69_IRQN);

// called this way, it uses the default address 0x40
Adafruit_PWMServoDriver pwm = Adafruit_PWMServoDriver();
boolean toClick;

void setup(void)
{
    // initialize serial communication
    #ifdef DEBUG_MODE
    Serial.begin(SERIAL_BAUD);
    #endif

    /* RFM69HCW INITIALIZATION ========================================= */

    #ifdef DEBUG_MODE
    Serial.println("UD RFM69HCW Initializing...");
    #endif

    // hard Reset the RFM module
    pinMode(FEATHER_RFM69_RST, OUTPUT);
    digitalWrite(FEATHER_RFM69_RST, HIGH);
    delay(100);
    digitalWrite(FEATHER_RFM69_RST, LOW);
    delay(100);

    // initialize radio
    node.initialize(FREQUENCY,NODEID,NETWORKID);
    if (IS_RFM69HCW) {
        // only for RFM69HCW & HW!
        node.setHighPower();
    }

    // power output ranges from 0 (5dBm) to 31 (20dBm)
    node.setPowerLevel(31);

    node.encrypt(EMERALDS_ENCRYPTKEY);

    #ifdef DEBUG_MODE
    pinMode(FEATHER_LED, OUTPUT);
    Serial.print("\nTransmitting at ");
    Serial.print(FREQUENCY == RF69_433MHZ ? 433 : FREQUENCY == RF69_868MHZ ? 868 : 915);
    Serial.println(" MHz");

    Serial.println("UD RFM69HCW Initialized!\n");
    #endif

    /* FUNCTION INITIALIZATION ========================================= */

    // set A0 to pull up input
    pinMode(A0, INPUT_PULLUP);

    // prepare servo
    pwm.begin();
    pwm.setPWMFreq(60);  // Analog servos run at ~60 Hz updates
    yield();

    toClick = false;
}

void loop(void)
{
    /* RF COMMS ======================================================== */

    // check if something was received (could be an interrupt from the radio)
    if (node.receiveDone()) {
        // print message received to serial
        #ifdef DEBUG_MODE
        Serial.print('[');
        Serial.print(node.SENDERID);
        Serial.print("] [");
        Serial.print((char*) node.DATA);
        Serial.print("]   [RX_RSSI:");
        Serial.print(node.RSSI);
        Serial.print("]");
        #endif

        // check if sender wanted an ACK
        if (node.ACKRequested()) {
            node.sendACK();
            #ifdef DEBUG_MODE
            Serial.println(" - ACK sent");
            #endif
        }

        #ifdef DEBUG_MODE
        else {
            Serial.println();
        }
        #endif

        // ready for clicking
        toClick = true;

        // delay for reception
        delay(250);
    }

    // put radio in RX mode
    node.receiveDone();

    // make sure all serial data is clocked out before sleeping the MCU
    #ifdef DEBUG_MODE
    Serial.flush();
    #endif

    /* CLICKER FUNCTION ================================================ */

    if (toClick || digitalRead(A0) == LOW) {
        for (uint16_t pulselen = 340; pulselen < SERVOMAX; pulselen++) {
            pwm.setPWM(0, 0, pulselen);
        }
        for (uint16_t pulselen = SERVOMAX; pulselen > 340; pulselen--) {
            pwm.setPWM(0, 0, pulselen);
        }
        toClick = false;
        delay(500);
    }
    else {
        pwm.setPWM(0, 0, 0);
    }
}
