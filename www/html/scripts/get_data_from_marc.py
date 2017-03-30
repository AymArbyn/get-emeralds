#!/usr/bin/env python

import serial
# ser = serial.Serial("/dev/ttyACM0", 9600)
ser = serial.Serial("COM12", 9600)

i = 0
while (True):
	i = i + 1
	if (ser.inWaiting() > 0):
		s = ser.readline()
		if 'FROM OUTLET'.encode() in s:
			print(s)
			break

	if (i > 1000000):
		break
