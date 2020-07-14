/**
 * Created by Nguyen Tuan Linh on 2016-07-23.
 */
var PushSubscriber = function (channel, callback) {
    this.channel = channel;
    this.callback = callback;
};
var PushClient = function (serverUrl, clientId, clientKey, clientSecret) {
    this.isConnected = false;
    this.connection = null;
    this.serverUrl = serverUrl;
    this.clientId = clientId;
    this.clientKey = clientKey;
    this.clientSecret = clientSecret;
    this.subscribers = [];
};
PushClient.prototype.register = function () {
    if (!this.isConnected) {
        var _self = this;
        loadOrtcFactory(IbtRealTimeSJType, function (factory, error) {
            if (error != null) {
                alert("Factory error: " + error.message);
            } else {
                if (factory != null) {
                    // Create Cloud Messaging client
                    _self.connection = factory.createClient();
                    // Set client properties
                    _self.connection.setId(_self.clientId);
                    // client.setConnectionMetadata('Some connection metadata');
                    _self.connection.setClusterUrl(_self.serverUrl);
                    _self.connection.onConnected = function (client) {
                        _self.isConnected = true;

                        for (var index in _self.subscribers) {
                            var subscriber = _self.subscribers[index];
                            _self.connection.subscribe(subscriber.channel, true, subscriber.callback);
                        }
                    };
                    _self.connection.onDisconnected = function (client) {
                        // Disconnected
                    };
                    _self.connection.onSubscribed = function (client, channel) {
                        // Subscribed to the channel 'channel'
                    };
                    _self.connection.onException = function (client, exception) {
                        // Exception occurred: 'exception'
                    };
                    _self.connection.onReconnecting = function (client) {
                        // Trying to reconnect
                    };
                    _self.connection.onReconnected = function (client) {
                        // Reconnected
                    };
                    _self.connection.connect(_self.clientKey, _self.clientSecret);
                }
            }
        });
    }
};
PushClient.prototype.subscribe = function (channel, callback) {
    this.subscribers.push(new PushSubscriber(channel, callback));
};
PushClient.prototype.send = function (channel, data) {
    this.connection.send(channel, JSON.stringify(data));
};

var _pushClient;
function setupPushClient(ortcServer, ortcClientId, ortcClientKey, ortcClientSecret) {
    _pushClient = new PushClient(
        ortcServer,
        ortcClientId,
        ortcClientKey,
        ortcClientSecret
    );
}
function pushClient() {
    if (_pushClient) {
        return _pushClient;
    }

    alert('Setup push client first');
}

function sendToPusher(channel, data) {
    pushClient().send(channel, data);
}

function subscribePusher(channel, callback) {
    pushClient().subscribe(channel, callback);
}

function registerPusher() {
    pushClient().register();
}