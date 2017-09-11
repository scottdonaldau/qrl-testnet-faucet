#!/bin/bash
# QRL Testnet Faucet

FROM="0" # Wallet 0
DEST=$1
AMOUNT=$2

expect << EOF
set timeout 10
spawn telnet localhost 2000
expect "QRL node connection established. Try starting with \"help\""
send "send $FROM $DEST $AMOUNT\r"
expect ">>>*"
EOF
