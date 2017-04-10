# [Mastodon.tools](http://mastodon.tools/)
[Open sources](https://github.com/DavidLibeau/mastodon-tools)

Embed [Mastodon](https://github.com/tootsuite/mastodon) statuses all around the web.


> You have a cool app using Mastodon API ? Get a free subdomain yourappname.Mastodon.tools ! [Learn more](/dns)

## Wordpress

[**Download plugin :arrow_down:**](https://wordpress.org/plugins/embed-mastodon/)

Embed Mastodon statuses in you blog or whatever Wordpress site.

Just write `[mastodon url="https://mastodon.xyz/@David/15605"][/mastodon]` in any of your article for

![Screenshot](http://mastodon.tools/wordpress/screenshot.png)


### Example

As seen at  https://blog.davidlibeau.fr/dev-test-embed-mastodon/


```
Example

[mastodon url="https://mastodon.xyz/@David/15605"][/mastodon]

https forced:
[mastodon url="https://mastodon.social/@Gargron/1"][/mastodon]

image:
[mastodon url="https://mastodon.xyz/@David/252287" height="500"][/mastodon]

spoiler:
[mastodon url="https://mastodon.xyz/@David/291091" height="250"][/mastodon]
```


> The Wordpress plugin may not work for some instances due to forced HTTPS. Feel free to help me to resolve [this issue](https://github.com/DavidLibeau/mastodon-tools/issues/1) !


## wall

1 file app to let you easily stream an #hashtag. Use it like a "Twitter wall" thing, in events, for example.

[Test it](https://dev.mastodon.tools/wall/) !

![Screenshot](http://mastodon.tools/wall/screenshot.png)


## ogp-share [indev]

[**share.Mastodon.tools :arrow_right:**](http://share.mastodon.tools/)

Share Mastodon statuses on all other social networks (Twitter, Facebook...).
Render your status in Twitter Card of Facebook with [OGP](http://ogp.me/) metatags.

```
http://share.mastodon.tools/?url=https://mastodon.xyz/@David/192108
```

### Exemple

![Screenshot Twitter](http://mastodon.tools/ogp-share/screenshots/tw.png)

![Screenshot Facebook](http://mastodon.tools/ogp-share/screenshots/fb.png)


## Dev

In dev folder, I am trying things.


## Support

[I'm @David@mastodon.xyz](https://mastodon.xyz/@David).

