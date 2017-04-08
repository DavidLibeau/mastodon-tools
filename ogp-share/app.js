const fs = require('fs');
const path = require('path');
var https = require('https');
var jsdom = require("jsdom");
const url = require('url');
var urlToImage = require('url-to-image');

var express = require('express');
var app = express();

app.use('/static', express.static(path.join(__dirname, 'db')))

app.get('/', function (req, res) {
    if (req.query.url != undefined && req.query.url != "") {
        var currentUrl = req.protocol + '://' + req.get('host') + req.originalUrl;
        jsdom.env(
            req.query.url, ["http://code.jquery.com/jquery.js"],
            function (err, window) {
                if (err) {
                    res.send('Error: ' + err);
                } else {
                    var atomUrl = window.$("link[type='application/atom+xml']");
                    if (!atomUrl.length) {
                        res.send('<h1>Error: Atom XML url not found</h1><p>The link may not be a valid Mastodon link (status deleted ?).</p>');
                    } else {
                        var imageName = user + "." + statusId + ".png";
                        var imageUrl= req.protocol + '://' + req.get('host') + "/static/" + imageName;
                        
                        atomUrl = atomUrl.attr("href");
                        var embedUrl = atomUrl.replace(".atom", "/embed");
                        var parsedUrl = url.parse(embedUrl);
                        urlToImage(embedUrl, "db"+imageName).then(function() {
                            console.log(imageName);
                        }).catch(function(err) {
                            console.error(err);
                        });
                        
                        var instance = parsedUrl.host;
                        var user = parsedUrl.path.split("/")[2];
                        var statusId = parsedUrl.path.split("/")[4];

                        var page = '<html><head>' +
                            '<title></title>' +
                            '<meta name="description" content="Mastodon status of @' + user + '@' + instance + '" />' +
                            '<meta name="keywords" content="mastodon" />' +
                            '<meta name="author" content="' + user + '" />' +
                            '<!-- FB -->' +
                            '<meta property="og:title" content="Mastodon status of @' + user + '@' + instance + '" />' +
                            '<meta property="og:type" content="article" />' +
                            '<meta property="og:image" content="' + imageUrl + '" />' +
                            '<meta property="og:url" content="' + currentUrl + '" />' +
                            '<meta property="og:description" content="Open in Mastodon" />' +
                            '<!-- Twitter -->' +
                            '<meta name="twitter:card" content="summary_large_image">' +
                            '<meta name="twitter:title" content="@' + user + '@' + instance + '" />' +
                            '<meta name="twitter:description" content="Open in Mastodon" />' +
                            '<meta name="twitter:image" content="' + imageUrl + '" />' +
                            '<meta name="twitter:image:alt" content="a Mastodon status of @' + user + '@' + instance + '" />' +
                            '</head><body>' +
                            '<h1>' + embedUrl + '</h1>' +
                            '<ul>' +
                            '<li>instance: ' + instance + '</li>' +
                            '<li>user: ' + user + '</li>' +
                            '<li>statusId: ' + statusId + '</li>' +
                            '</ul>' +
                            '<script>window.location = "' + req.query.url + '";</script>'
                        '</body></html>';

                        res.send(page);
                    }
                }

            });

    } else {
        res.send('url required');
    }

});


app.listen(9999);