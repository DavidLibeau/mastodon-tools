# mastodon-widget
Embed Mastodon messages all around the web.


> The idea of this script is to provide an embeded version of a Mastodon status. With that, we can embed our status on other websites.

### Process

User enter a status link -> cURL the status link to find the XML Atom link -> cURL XML Atom version of the status -> display the XML with an XSLT stylesheet
