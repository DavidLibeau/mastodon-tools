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
                    res.send('Error (res.send): ' + err);
                } else {
                    var atomUrl = window.$("link[type='application/atom+xml']");
                    if (!atomUrl.length) {
                        res.send('<h1>Error: Atom XML url not found</h1><p>The link may not be a valid Mastodon link (status deleted ?).</p>');
                    } else {
                        atomUrl = atomUrl.attr("href");
                        var embedUrl = atomUrl.replace(".atom", "/embed");
                        var parsedUrl = url.parse(embedUrl);

                        var instance = parsedUrl.host;
                        var user = parsedUrl.path.split("/")[2];
                        var statusId = parsedUrl.path.split("/")[4];

                        var imageName = user + "-" + instance + "-" + statusId + ".jpg";
                        var imageUrl = req.protocol + '://' + req.get('host') + "/static/" + imageName;

                        fs.stat("db/"+imageName, function (err, stat) {
                            if (err == null) {
                                //File exist
                            } else {
                                var options = {
                                    width: 600,
                                    height: 314,
                                    fileType: 'jpeg',
                                    fileQuality: 20,
                                    cropWidth: 600,
                                    cropHeight: 314,
                                    cropOffsetLeft: 0,
                                    cropOffsetTop: 0
                                }
                                urlToImage(req.hostname + "/render/?url=" + embedUrl.replace("https://", "http://"), "db/" + imageName, options).then(function () {
                                    //console.log(imageName);
                                }).catch(function (err) {
                                    console.log("Error (urlToimage.catch):");
                                    console.error(err);
                                });
                            }
                        });


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
                            '</head><body style="background-color:#444b5d;">' +
                            '<p><a href="' + req.query.url + '" style="color:white; font-family:sans-serif;">You will be redirected, <strong>click here if nothing happens</strong>.</a></p>'+
                            '<script>window.location = "' + req.query.url + '";</script>'+
                        '</body></html>';
                        
                        /*
                            '<h1>' + embedUrl + '</h1>' +
                            '<ul>' +
                            '<li>instance: ' + instance + '</li>' +
                            '<li>user: ' + user + '</li>' +
                            '<li>statusId: ' + statusId + '</li>' +
                            '</ul>' +
                        */

                        res.send(page);
                    }
                }

            });

    } else {
        res.send('Error: url required');
    }

});

app.get('/render/', function (req, res) {
    if (req.query.url != undefined && req.query.url != "") {
        var page = '<html><head>' +
            '<title>Render</title>' +
            '<style>body{width:600px; height:314px; overflow:hidden; background-color:#444b5d;} iframe{margin: 40px; border:0; width:500px; overflow: hidden;}</style>' +
            '</head><body>' +
            '<iframe src="' + req.query.url + '"></iframe>' +
            '</body></html>';

        res.send(page);
    }
});


app.listen(9999);