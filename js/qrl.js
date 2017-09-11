// Current detail wallet address
var currentDetailAddress = 0;

var faucetUrl = "http://qrl-faucet.folio.ninja/";
var apiUrl = "http://qrl-faucet.folio.ninja:8080/api/";
var faucetAddress = "Q2fa73c159a71a84934d91180aa86f954a369b01f6f7c760ebee949b73db6048f7caf";


// Init Transactions table
var TransT = $("#TransT").DataTable({
    "order": [[1, "desc"]]
});

// Manage view state
// 0 = main page
// 1 = show node stats
var viewState = 0;

// Draw a row in transactions table
function drawTransRow(timestamp, amount, txHashLink, block, txfrom, txto, fee, address, txnsubtype) {
    
    // Detect txn direction
    var txnDirection = "";
    if (address === txfrom) {
        // Sent from this wallet
        txnDirection = '<div style=\"text-align: center\"><i class=\"sign out icon\"></i></div>';
    } else {
        // Sent to this wallet
        txnDirection = '<div style=\"text-align: center\"><i class=\"sign in icon\"></i></td></div>';
    }

    // Override txnDirection is txnsubtype is COINBASE
    if(txnsubtype == "COINBASE") {
        txnDirection = '<div style=\"text-align: center\"><i class=\"yellow lightning icon\"></i></td></div>';
    }

    // Generate timestamp string
    var thisMoment = moment.unix(timestamp);
    var timeString  = moment(thisMoment).format("HH:mm D MMM YYYY");

    // Add row
    TransT.row.add([txHashLink, block, timeString, txfrom, txnDirection, txto, amount, fee]);
}



// Gets detail about the running node
function getNodeInfo(hideDimmer = false) {
    // Change view state
    viewState = 1;

    // Dimmer does not show for auto refreshes.
    if(hideDimmer === false) {
        $('.dimmer').show();
    }

    $('#show-main').hide();
    $('#show-node-stats').show();

    $.ajax({
        url: apiUrl + 'stats',
        dataType: 'json',
        type: "GET",
        success: function(data) {
            $('.dimmer').hide();


            $('#network').text(':' + data.network);
            var x = moment.duration(data.network_uptime,'seconds').format("d[d] h[h] mm[min]");
            $('#uptime').text(x);
            $('#nodes').text(data.nodes);
            var x = moment.duration(data.block_time_variance,'seconds');
            x = Math.round(x/10)/100;
            $('#variance').text(x + 's');
            var x = moment.duration(data.block_time,'seconds').format("s[s]");
            $('#blocktime').text(x);
            $('#blockheight').text(data.blockheight);
            $('#validators').text(data.stake_validators);
            $('#PCemission').text(data.staked_percentage_emission + '%');
            $('#epoch').text(data.epoch);
            var x = data.emission;
            x = (Math.round(x * 10000)) / 10000;
            $('#emission').text(x);
            var x = data.unmined;
            x = (Math.round(x * 10000)) / 10000;
            $('#unmined').text(x);
            $('#reward').text(data.block_reward);
            $('#nodeversion').text(data.version);
        },
        error: function(data) {
            $('.dimmer').hide();
        }
    });
}


// Draws address detail to page
function drawMain(addressDetail, usdvalue) {
    // Change view state
    viewState = 0;

    // Clear list first
    $('#walletdetail').empty();

    // Show address
    $('#addressHeading').text(faucetAddress);

    // Only show these details if we get a successful reply from the API
    if(addressDetail.status === "ok") {
        $('#balance').text(addressDetail.state.balance);
        $('#nonce').text(addressDetail.state.nonce);
        $('#transactions').text(addressDetail.state.transactions);
        $('#sigsremaining').text("TODO");

        TransT.clear();
        _.each(addressDetail.transactions, function(object) {

            // Grab values from API
            var thisTimestamp = (object.timestamp === undefined) ? "Unknown" : object.timestamp;
            var thisAmount = (object.amount === undefined) ? "Unknown" : object.amount;
            var thisBlock = (object.block === undefined) ? "Unknown" : object.block;
            var thisTxHash = (object.txhash === undefined) ? "Unknown" : object.txhash;
            var thisTxFrom = (object.txfrom === undefined) ? "Unknown" : object.txfrom;
            var thisTxTo =  (object.txto === undefined) ? "Unknown" : object.txto;
            var thisFee = (object.fee === undefined) ? 0 : object.fee;
            var thisAddress = addressDetail.state.address;
            var thisSubType = (object.subtype === undefined) ? "Unknown" : object.subtype;

            // Generate links
            thisBlock = '<a target="_blank" href="http://qrlexplorer.info/block/'+thisBlock+'">'+thisBlock+'</a>';
            txHashLink = '<a target="_blank" href="http://qrlexplorer.info/tx/'+thisTxHash+'">'+thisTxHash+'</a>';
            thisTxFrom = '<a target="_blank" href="http://qrlexplorer.info/a/'+thisTxFrom+'">'+thisTxFrom+'</a>';
            thisTxTo = '<a target="_blank" href="http://qrlexplorer.info/a/'+thisTxTo+'">'+thisTxTo+'</a>';

            drawTransRow(thisTimestamp, thisAmount, txHashLink, thisBlock, thisTxFrom, thisTxTo, thisFee, thisAddress, thisSubType);
        });
        TransT.columns.adjust().draw(true);

        // Attempt to get USD Value of wallet
        $.ajax({
            url: 'http://cors-anywhere.herokuapp.com/https://www.folio.ninja/api/v1/quote?base=QRL&quote=USD&amount=' + addressDetail.state.balance,
            dataType: 'json',
            jsonpCallback: 'callback',
            success: function(folioNinjaReply) {
                $('#usdvalue').text("$" + folioNinjaReply.response.quote);
            }
        });
    } else {
        TransT.clear();
        TransT.columns.adjust().draw(true);
        $('#balance').text("0");
        $('#pendingbalance').text("0");
        $('#nonce').text("0");
        $('#transactions').text("0");
        $('#sigsremaining').text(sigSplit[0]);
        $('#usdvalue').text("0");
    }

    // Remove dimmer
    $('.dimmer').hide();
}



// Gets details about a single address, and passes to draw function to rendor to screen.
function getMain(hideDimmer = false) {

    // Dimmer does not show for auto refreshes.
    // Also don't reset data
    if(hideDimmer === false) {
        $('.dimmer').show();

        // Clear to and amount onload
        $('#to').val("");
        $('#amount').val("");
        $('#addressHeading').text("");
        $('#balance').text("");
        $('#pendingbalance').text("");
        $('#nonce').text("");
        $('#transactions').text("");
        $('#sigsremaining').text("");
        $('#usdvalue').text("");
    }

    // Change view
    $('#show-node-stats').hide();
    $('#show-main').show();
    
    $.ajax({
        url: apiUrl + 'address/' + faucetAddress,
        success: function(addressDetail) {
            drawMain(addressDetail);
        },
        error: function(addressDetail) {
            drawMain(addressDetail);
        }
    });
}




// Modal to show txn result to screen
function drawTxnResult(txnResult) {
    getMain();

    $('#domModalTitle').text('Transaction Results');
    $('#domModalBody').html(txnResult);
    $('#domModal').modal('show');

    // Hide loading pane
    $('.dimmer').hide();
}


// Creates a transaction in the network
function sendQuanta() {
    $('.dimmer').show();

    var from = faucetAddress;
    var to = $('#to').val();
    var amount = $('#amount').val();

    $.ajax({
        url: faucetUrl + '/process/send.php',
        dataType: 'text',
        type: "POST",
        data: JSON.stringify( { "to": to, "amount": amount } ),
        processData: false,
        success: function(data) {
            drawTxnResult(data);            
        },
        error: function(data) {
            drawTxnResult(data);
        }
    });
}


// Show addresses on ready
$( document ).ready(function() {
    // Hide node stats
    $('#show-node-stats').hide();

    // Rendor addresses to screen.
    getMain();
});


// 20 second refresh
window.setInterval(function() {
    // Refresh addresses
    if(viewState === 0) {
        getMain(true);
    }

    // Refresh node stats
    if(viewState === 1) {
        getNodeInfo(true);
    }
}, 20000);
