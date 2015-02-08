# Zork

This repository contains attempts at porting the text-based adventure game [*Zork*][1] from its [original source code][2] written in the [MIT Design Language][3] (MDL).

## Rationale

You might be wondering, "why do this?" [And you may well ask, "Why climb the highest mountain? Why, ~~35~~ 88 years ago, fly the Atlan—"][4] Okay, the real reason is because *Zork* holds a special place in my heart and I wanted to see if it'd be possible to replicate it in its original form—feature for feature, bug for bug—in modern languages.

## Caveats

The original incarnation of *Zork* was written in the late '70s in a language that has long been obsolete. In order to create a faithful adaptation, the original algorithms, procedures, and program design have been ported without consideration of modern programming principles. Concepts and structures that did not exist in MDL—for example, objects—are not used. This repository should not be considered a paragon of current development practices, but an exploration of a historical moment in game development.

With that in mind, I have made a few conceits:

- Concepts and data structures that existed in MDL but do not exist in certain, more modern languages (e.g. enumerable types in PHP) are replicated as best as possible while still maintaining the spirit of the original code.
- Namespacing of functions (and classes when they are necessary) is used.

## Installation and usage

Installation is done using [Composer][5] via [Packagist][6]:

```sh
composer create-project itafroma/zork:dev-master --dev
```

At this stage of development, there is no working binary, but you can run tests using [PHPUnit][7]:

```sh
cd zork
./vendor/bin/phpunit
```

## Acknowledgments

The original *Zork* was designed and [implemented][5] between 1977 and 1979 by [Tim Anderson][6], [Marc Blank][7], [Bruce Daniels][8], and [Dave Lebling][9] working out of the MIT Laboratory for Computer Science Dynamic Modeling System ([MIT-DMS][10]).

I am using the reference manual *The MDL Programming Language* by S. W. Galley and [Greg Pfister][11] to facilitate the porting process.

## Copyright and license

The original *Zork* source code is copyright © 1978 Massachusetts Institute of Technology. All rights reserved.

Where applicable, the ported source code is copyright © 2015 Mark Trapp. All rights reserved. The ported code is made available under the MIT license. A copy of the license can be found in the `LICENSE` file.

[1]: http://en.wikipedia.org/wiki/Zork "Wikipedia article on Zork"
[2]: https://github.com/itafroma/zork-mdl "Source code repository for MDL Zork"
[3]: http://en.wikipedia.org/wiki/MDL_(programming_language) "Wikipedia article on MDL"
[4]: http://er.jsc.nasa.gov/seh/ricetalk.htm "Transcript of JFK's Moon Speech at Rice Stadium in 1962"
[5]: https://getcomposer.org "Composer website"
[6]: https://packagist.org "Packagist website"
[7]: http://en.wikipedia.org/wiki/Implementer_(video_games) "Wikipedia article on implmenters"
[8]: http://en.wikipedia.org/wiki/Tim_Anderson_(Zork) "Wikipedia article on Tim Anderson"
[9]: http://www.infocom-if.org/authors/blank.html "Infocom author page on Marc Blank"
[10]: http://en.wikipedia.org/wiki/Bruce_Daniels "Wikipedia article on Bruce Daniels"
[11]: http://www.infocom-if.org/authors/lebling.html "Infocom author page on Dave Lebling"
[12]: http://pdp-10.trailing-edge.com/mit_emacs_170_teco_1220/01/info/mit-dm.txt.html "Information on MIT-DMS"
[13]: http://perilsofparallel.blogspot.com "Greg Pfister's blog"
