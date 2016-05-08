# Zork

This repository contains an attempt at porting the text-based adventure game [*Zork*][1] from its [original source code][2] written in the [MIT Design Language][3] (MDL) to PHP.

[![Build Status](https://travis-ci.org/itafroma/zork-php.svg?branch=master)](https://travis-ci.org/itafroma/zork-php)
[![Code Coverage](https://scrutinizer-ci.com/g/itafroma/zork-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/itafroma/zork-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/itafroma/zork-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/itafroma/zork-php/?branch=master)

## Rationale

You might be wondering, "why do this?" [And you may well ask, "Why climb the highest mountain? Why, ~~35~~ 88 years ago, fly the Atlan—"][4] Okay, the real reason is because *Zork* holds a special place in my heart and I wanted to see if it'd be possible to replicate it in its original form—feature for feature, bug for bug—in modern languages.

## Caveats

The original incarnation of *Zork* was written in the late '70s in a language that has long been obsolete. In order to create a faithful adaptation, the original algorithms, procedures, and program design have been ported without consideration of modern programming principles. Concepts and structures that did not exist in MDL—for example, objects—are not used. This repository should not be considered a paragon of current development practices, but an exploration of a historical moment in game development.

With that in mind, I have made a few conceits:

- Concepts and data structures that existed in MDL but do not exist in certain, more modern languages (e.g. enumerable types in PHP) are replicated as best as possible while still maintaining the spirit of the original code.
- Namespacing of functions (and classes when they are necessary) is used.

## Roadmap

You can follow along with development on my blog in a series of posts I'm calling "[Porting Zork][5]". This project will follow the [Semantic Versioning][6] standard: the first numbered unstable version, 0.1.0, will contain a running executable (though not a complete game). The 1.0.0 release will be a complete port.

## Installation and usage

Installation is done using [Composer][7] via [Packagist][8]:

```sh
composer create-project itafroma/zork:dev-master
```

At this stage of development, there is no working binary, but you can run tests using [PHPUnit][9]:

```sh
cd zork
./vendor/bin/phpunit --configuration phpunit.xml.dist
```

## Acknowledgments

The original *Zork* was designed and [implemented][10] between 1977 and 1979 by [Tim Anderson][11], [Marc Blank][12], [Bruce Daniels][13], and [Dave Lebling][14] working out of the MIT Laboratory for Computer Science Dynamic Modeling System ([MIT-DMS][15]).

I am using the reference manual *The MDL Programming Language* by S. W. Galley and [Greg Pfister][16] to facilitate the porting process.

## Copyright and license

The original *Zork* source code is copyright © 1978 Massachusetts Institute of Technology. All rights reserved.

Where applicable, the ported source code is copyright © 2015 Mark Trapp. All rights reserved. The ported code is made available under the MIT license. A copy of the license can be found in the `LICENSE` file.

## Development disclaimer

This a personal project in its early stages of development and planning. It is not ready for collaboration or a guarantee of fitness. It's being made available for transparency (and so I have something to point to when I want to talk about it), but be warned that:

- Support requests via GitHub issues or email will go unanswered and ignored
- Pull requests will be summarily rejected
- Public progress may be minimal to non-existent for long stretches at a time

You're welcome to use what's here under the terms of its license (if one is available) or the principles of fair use (if one is not). If you like the idea/premise behind this project, I would suggest starting over the way you'd want to do it yourself as I am very unlikely to accept future contributions forked off of, or derived from, this project's current stage of development.

[1]: http://en.wikipedia.org/wiki/Zork "Wikipedia article on Zork"
[2]: https://github.com/itafroma/zork-mdl "Source code repository for MDL Zork"
[3]: http://en.wikipedia.org/wiki/MDL_(programming_language) "Wikipedia article on MDL"
[4]: http://er.jsc.nasa.gov/seh/ricetalk.htm "Transcript of JFK's Moon Speech at Rice Stadium in 1962"
[5]: https://marktrapp.com/blog/2015/01/27/porting-zork-part-1/ "Porting Zork — Part 1: Introduction"
[6]: http://semver.org "Semantic Versioning website"
[7]: https://getcomposer.org "Composer website"
[8]: https://packagist.org "Packagist website"
[9]: https://phpunit.de "PHPUnit website"
[10]: http://en.wikipedia.org/wiki/Implementer_(video_games) "Wikipedia article on implmenters"
[11]: http://en.wikipedia.org/wiki/Tim_Anderson_(Zork) "Wikipedia article on Tim Anderson"
[12]: http://www.infocom-if.org/authors/blank.html "Infocom author page on Marc Blank"
[13]: http://en.wikipedia.org/wiki/Bruce_Daniels "Wikipedia article on Bruce Daniels"
[14]: http://www.infocom-if.org/authors/lebling.html "Infocom author page on Dave Lebling"
[15]: http://pdp-10.trailing-edge.com/mit_emacs_170_teco_1220/01/info/mit-dm.txt.html "Information on MIT-DMS"
[16]: http://perilsofparallel.blogspot.com "Greg Pfister's blog"
