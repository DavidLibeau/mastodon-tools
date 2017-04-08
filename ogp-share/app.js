const url = require('url');
var https = require('https');

var express = require('express');
var app = express();

app.get('/', function (req, res) {
    if (req.query.url != undefined && req.query.url != "") {
        var mastodonUrl = url.parse(req.query.url);

        var options = {
            host: mastodonUrl.host,
            port: 443,
            path: mastodonUrl.path,
            method: 'GET'
        };

        var httpget = https.request(options, function (httpgetres) {
            console.log("statusCode: ", httpgetres.statusCode);
            console.log("headers: ", httpgetres.headers);

            httpgetres.on('data', function (data) {
                var atomUrl;
                var user;
                var instance;
                var statusId;
                var image = "db/" + user + "." + statusId+".png";
                var page = '<html><head>' +
                    '<title></title>' +
                    '<meta name="description" content="" />' +
                    '<meta name="keywords" content="" />' +
                    '<meta name="author" content="" />' +
                    '<!-- FB -->' +
                    '<meta property="og:title" content="Mastodon status of @' + user + '@' + instance + '" />' +
                    '<meta property="og:type" content="article" />' +
                    '<meta property="og:image" content="" />' +
                    '<meta property="og:url" content="" />' +
                    '<meta property="og:description" content="" />' +
                    '<!-- Twitter -->' +
                    '<meta name="twitter:card" content="summary_large_image">' +
                    '<meta name="twitter:title" content="Mastodon status of @' + user + '@' + instance + '" />' +
                    '<meta name="twitter:description" content="See more at ' + instance + '" />' +
                    '<meta name="twitter:image" content="' + image + '" />' +
                    '<meta name="twitter:image:alt" content="a Mastodon status of @' + user + '@' + instance + '" />' +
                    '</head><body>' +
                    '<h1>' + url + '</h1>' +
                    '<h1>' + atomUrl + '</h1>' +
                    '<code>' + data + '</code>' +
                    '</body></html>';

                res.send(page);

            });
        });
        httpget.end();

    } else {
        res.send('url required');
    }

});


app.listen(9999);