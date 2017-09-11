<html>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
        <title>QRL Testnet Faucet</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.css">
        <link rel="stylesheet" href="css/styles.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
        <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
        <script src="js/JPL.dataTables.semanticui.js"></script>
    </head>
    <body>
        <div class="ui tiny fixed inverted menu" id="menu">
            <div class="ui container">
                <a href="/" class="header item">
                    <img class="logo" src="assets/Q@2x.png">
                </a>
                <a href="/" class="header item" style="font-weight: inherit;">
                    QRL Testnet Faucet
                </a>
                <a href="#" onclick="getNodeInfo()" class="header item" style="font-weight: inherit;">
                    Network Status
                </a>
                <div class="ui simple dropdown item pull_right">
                    About <i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" target="_blank" href="http://theqrl.org/whitepaper/QRL_whitepaper.pdf">QRL Whitepaper</a>
                        <a class="item" target="_blank" href="https://github.com/theQRL/QRL">Github</a>
                        <a class="item" target="_blank" href="http://theQRL.org">theQRL.org</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui sidebar inverted vertical menu">
            <a class="item header" href="/"><img class="logo" src="assets/Q@2x.png" style="max-width: 50px; padding-bottom: 10px;"></a>
            <div class="item header">QRL Testnet Faucet</div>
            <a class="item" href="/">Withdraw QRL</a>
            <a class="item" href="#" onclick="getNodeInfo()">Network Status</a>

            <div class="item header">About</div>
            <a class="item" target="_blank" href="http://theqrl.org/">&raquo; theQRL.org</a>
            <a class="item" target="_blank" href="http://theqrl.org/whitepaper/QRL_whitepaper.pdf">&raquo; White Paper</a>
            <a class="item" target="_blank" href="https://github.com/theQRL/QRL">&raquo; Github</a>
        </div>
        <div class="pusher">
            <div class="ui fixed inverted menu" id="m_menu">
                <div class="ui container" id="m_btn">
                    <div class="icon item">
                        <a id="hamburger"><i class="content icon"></i> Menu</a>
                    </div>
                </div>
            </div>
            <div class="main container">
                <div class="ui active dimmer">
                    <div class="ui massive text loader" id="dimmerText">Please Wait ...</div>
                </div>
                <div class="ui stackable grid">
                    <div class="twelve wide centered column">
                        <div class="ui fluid card">
                            <div class="content">
                                <div class="header">QRL Testnet Faucet</div>
                            </div>

                            <!-- Show Single Wallet -->
                            <div id="show-main" class="content">

                                <!-- Address Heading - Show Address -->
                                <b id="addressHeading" style="font-size:1.5em"></b>
                                
                                <br /><br />
                                
                                <div class="ui divided horizontal selection list" id="petefix">
                                    <a class="item">
                                        <div class="ui green horizontal label" id="balance"></div>
                                        Faucet QRL Balance
                                    </a>
                                    <br />
                                    <a class="item">
                                        <div class="ui orange horizontal label" id="usdvalue"></div>
                                        USD Value
                                    </a>
                                    <a class="item">
                                        <div class="ui purple horizontal label" id="nonce"></div>
                                        Nonce
                                    </a>
                                    <a class="item">
                                        <div class="ui blue horizontal label" id="transactions"></div>
                                        Transactions
                                    </a>
                                </div>

                                <br /><br /><br />

                                <div class="extra content">
                                    <h4 class="ui sub header dash_header">Send Testnet QRL</h4>
                                    <label for="to">To</label><br />
                                    <input type="text" id="to" name="to" style="max-width: 100%; width: 540px;" />
                                    <br /><br />
                                    <label for="amount">Amount - Max 100</label><br />
                                    <input type="text" id="amount" name="amount" style="max-width: 100%;  width: 540px;"  />
                                    <br /><br />
                                </div>
                                <div class="extra content">
                                    <button style="float:left;" onclick="sendQuanta()" class="ui green button">Send Testnet QRL</button>
                                </div>

                                <br /><br /><br />

                                <div id="Saddress">
                                    <div class="ui top attached tabular menu">
                                        <a class="active item" data-tab="first">Transactions</a>
                                    </div>
                                    <div class="ui bottom attached active tab segment" data-tab="first">
                                        <div class="ui stackable grid">
                                            <div class="sixteen wide column">
                                                <table id="TransT" class="ui single line unstackable celled selectable table">
                                                    <thead>
                                                        <th>Txhash</th>
                                                        <th>Block</th>
                                                        <th>Timestamp</th>
                                                        <th>From</th>
                                                        <th>&nbsp;</th>
                                                        <th>To</th>
                                                        <th>Value</th>
                                                        <th>Fee</th>
                                                    </thead>
                                                    <tbody id="TransT-table">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <!-- Show Node Stats -->
                            <div id="show-node-stats" class="content">
                                <h4 class="ui sub header dash_header">Testnet Faucet Node Details</h4>
                                <div class="ui small feed" id="nodedetails">
                                  
                                    <div class="content">
                                        <div class="ui divided list">
                                            <strong style="color:#000;"></strong><strong style="color:#000;">Network</strong>
                                            <a class="item">
                                                id <div class="right floated content"><div class="ui red horizontal label" id="network"></div></div>
                                            </a>
                                            <a class="item">
                                                uptime <div class="right floated content"><div class="ui purple horizontal label" id="uptime"></div></div>
                                            </a>
                                            <a class="item">
                                                nodes <div class="right floated content"><div class="ui blue horizontal label" id="nodes"></div></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="ui divided list">
                                            <strong style="color:#000;"></strong><strong style="color:#000;">Proof-of-stake</strong>
                                            <a class="item">
                                                stake validators <div class="right floated content"><div class="ui red horizontal label" id="validators"></div> </div>
                                            </a>
                                            <a class="item">
                                                staked emission <div class="right floated content"><div class="ui purple horizontal label" id="PCemission"></div>  </div>
                                            </a>
                                            <a class="item">
                                                epoch <div class="right floated content"><div class="ui blue horizontal label" id="epoch"></div></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="ui divided list">
                                            <strong style="color:#000;"></strong><strong style="color:#000;">Blocks</strong>
                                            <a class="item">
                                                block time variance <div class="right floated content"><div class="ui red horizontal label" id="variance"></div></div>
                                            </a>
                                            <a class="item">
                                                block time <div class="right floated content"><div class="ui purple horizontal label" id="blocktime"></div></div>
                                            </a>
                                            <a class="item">
                                                blockheight <div class="right floated content"><div class="ui blue horizontal label" id="blockheight"></div></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="ui divided list">
                                            <strong style="color:#000;"></strong><strong style="color:#000;">Coin supply</strong>
                                            <a class="item">
                                                emission <div class="right floated content"><div class="ui red horizontal label" id="emission"></div></div>
                                            </a>
                                            <a class="item">
                                                unmined <div class="right floated content"><div class="ui purple horizontal label" id="unmined"></div></div>
                                            </a>
                                            <a class="item">
                                                block reward <div class="right floated content"><div class="ui blue horizontal label" id="reward"></div></div>
                                            </a>
                                        </div>
                                    </div>


                                </div>
                                <div class="extra content">
                                <button onclick="getMain()" class="ui button">Back to Home</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ui inverted vertical footer segment">
                <div class="ui center aligned container">
                    <a href="#"><img width="91" src="assets/ringedQ@2x.png"></a>
                    <p></p>
                    <div class="ui center aligned container">
                        <div class="ui horizontal inverted small divided link list">
                            <a class="item" href="http://theqrl.org/">theQRL.org</a>
                            <a class="item" href="http://theqrl.org/whitepaper/QRL_whitepaper.pdf">White Paper</a>
                            <a class="item" href="https://github.com/theQRL/QRL">Github</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui modal" id="domModal">
            <i class="close icon"></i>
            <div class="header" id="domModalTitle">
                
            </div>
            <div class="content">
                <div class="description" id="domModalBody">
                    
                </div>
            </div>
            <div class="actions">
                <div class="ui cancel button">Ok</div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-duration-format/1.3.0/moment-duration-format.js"></script>
        <script src="js/qrl.js"></script>
        <script>
        $('.ui.dropdown').dropdown();
        $('.sidebar').first().sidebar('attach events', '#hamburger', 'show');
        </script>
    </body>
</html>