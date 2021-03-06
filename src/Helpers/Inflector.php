<?php

namespace Elab\Lite\Helpers;

/**
 */

/**
 * PhInflector based on BaseInflector from Yii 2.0 - this is just a simple PHP 5.2 backport
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Tobias Munk <schmunk@usrbin.de>
 * @author Kai Ziefle <k.ziefle@herzogkommunikation.de>
 * @since 0.1
 */
class Inflector
{
    /**
     * @var array the rules for converting a word into its plural form.
     * The keys are the regular expressions and the values are the corresponding replacements.
     */
    public static $plurals = array(
        '/([nrlm]ese|deer|fish|sheep|measles|ois|pox|media)$/i' => '\1',
        '/^(sea[- ]bass)$/i' => '\1',
        '/(m)ove$/i' => '\1oves',
        '/(f)oot$/i' => '\1eet',
        '/(h)uman$/i' => '\1umans',
        '/(s)tatus$/i' => '\1tatuses',
        '/(s)taff$/i' => '\1taff',
        '/(t)ooth$/i' => '\1eeth',
        '/(quiz)$/i' => '\1zes',
        '/^(ox)$/i' => '\1\2en',
        '/([m|l])ouse$/i' => '\1ice',
        '/(matr|vert|ind)(ix|ex)$/i' => '\1ices',
        '/(x|ch|ss|sh)$/i' => '\1es',
        '/([^aeiouy]|qu)y$/i' => '\1ies',
        '/(hive)$/i' => '\1s',
        '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
        '/sis$/i' => 'ses',
        '/([ti])um$/i' => '\1a',
        '/(p)erson$/i' => '\1eople',
        '/(m)an$/i' => '\1en',
        '/(c)hild$/i' => '\1hildren',
        '/(buffal|tomat|potat|ech|her|vet)o$/i' => '\1oes',
        '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
        '/us$/i' => 'uses',
        '/(alias)$/i' => '\1es',
        '/(ax|cris|test)is$/i' => '\1es',
        '/s$/' => 's',
        '/^$/' => '',
        '/$/' => 's',
    );
    /**
     * @var array the rules for converting a word into its singular form.
     * The keys are the regular expressions and the values are the corresponding replacements.
     */
    public static $singulars = array(
        '/([nrlm]ese|deer|fish|sheep|measles|ois|pox|media|ss)$/i' => '\1',
        '/^(sea[- ]bass)$/i' => '\1',
        '/(s)tatuses$/i' => '\1tatus',
        '/(f)eet$/i' => '\1oot',
        '/(t)eeth$/i' => '\1ooth',
        '/^(.*)(menu)s$/i' => '\1\2',
        '/(quiz)zes$/i' => '\\1',
        '/(matr)ices$/i' => '\1ix',
        '/(vert|ind)ices$/i' => '\1ex',
        '/^(ox)en/i' => '\1',
        '/(alias)(es)*$/i' => '\1',
        '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '\1us',
        '/([ftw]ax)es/i' => '\1',
        '/(cris|ax|test)es$/i' => '\1is',
        '/(shoe|slave)s$/i' => '\1',
        '/(o)es$/i' => '\1',
        '/ouses$/' => 'ouse',
        '/([^a])uses$/' => '\1us',
        '/([m|l])ice$/i' => '\1ouse',
        '/(x|ch|ss|sh)es$/i' => '\1',
        '/(m)ovies$/i' => '\1\2ovie',
        '/(s)eries$/i' => '\1\2eries',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/([lr])ves$/i' => '\1f',
        '/(tive)s$/i' => '\1',
        '/(hive)s$/i' => '\1',
        '/(drive)s$/i' => '\1',
        '/([^fo])ves$/i' => '\1fe',
        '/(^analy)ses$/i' => '\1sis',
        '/(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
        '/([ti])a$/i' => '\1um',
        '/(p)eople$/i' => '\1\2erson',
        '/(m)en$/i' => '\1an',
        '/(c)hildren$/i' => '\1\2hild',
        '/(n)ews$/i' => '\1\2ews',
        '/eaus$/' => 'eau',
        '/^(.*us)$/' => '\\1',
        '/s$/i' => '',
    );
    /**
     * @var array the special rules for converting a word between its plural form and singular form.
     * The keys are the special words in singular form, and the values are the corresponding plural form.
     */
    public static $specials = array(
        'atlas' => 'atlases',
        'beef' => 'beefs',
        'brother' => 'brothers',
        'cafe' => 'cafes',
        'child' => 'children',
        'cookie' => 'cookies',
        'corpus' => 'corpuses',
        'cow' => 'cows',
        'curve' => 'curves',
        'foe' => 'foes',
        'ganglion' => 'ganglions',
        'genie' => 'genies',
        'genus' => 'genera',
        'graffito' => 'graffiti',
        'hoof' => 'hoofs',
        'loaf' => 'loaves',
        'man' => 'men',
        'money' => 'monies',
        'mongoose' => 'mongooses',
        'move' => 'moves',
        'mythos' => 'mythoi',
        'niche' => 'niches',
        'numen' => 'numina',
        'occiput' => 'occiputs',
        'octopus' => 'octopuses',
        'opus' => 'opuses',
        'ox' => 'oxen',
        'penis' => 'penises',
        'sex' => 'sexes',
        'soliloquy' => 'soliloquies',
        'testis' => 'testes',
        'trilby' => 'trilbys',
        'turf' => 'turfs',
        'wave' => 'waves',
        'Amoyese' => 'Amoyese',
        'bison' => 'bison',
        'Borghese' => 'Borghese',
        'bream' => 'bream',
        'breeches' => 'breeches',
        'britches' => 'britches',
        'buffalo' => 'buffalo',
        'cantus' => 'cantus',
        'carp' => 'carp',
        'chassis' => 'chassis',
        'clippers' => 'clippers',
        'cod' => 'cod',
        'coitus' => 'coitus',
        'Congoese' => 'Congoese',
        'contretemps' => 'contretemps',
        'corps' => 'corps',
        'debris' => 'debris',
        'diabetes' => 'diabetes',
        'djinn' => 'djinn',
        'eland' => 'eland',
        'elk' => 'elk',
        'equipment' => 'equipment',
        'Faroese' => 'Faroese',
        'flounder' => 'flounder',
        'Foochowese' => 'Foochowese',
        'gallows' => 'gallows',
        'Genevese' => 'Genevese',
        'Genoese' => 'Genoese',
        'Gilbertese' => 'Gilbertese',
        'graffiti' => 'graffiti',
        'headquarters' => 'headquarters',
        'herpes' => 'herpes',
        'hijinks' => 'hijinks',
        'Hottentotese' => 'Hottentotese',
        'information' => 'information',
        'innings' => 'innings',
        'jackanapes' => 'jackanapes',
        'Kiplingese' => 'Kiplingese',
        'Kongoese' => 'Kongoese',
        'Lucchese' => 'Lucchese',
        'mackerel' => 'mackerel',
        'Maltese' => 'Maltese',
        'mews' => 'mews',
        'moose' => 'moose',
        'mumps' => 'mumps',
        'Nankingese' => 'Nankingese',
        'news' => 'news',
        'nexus' => 'nexus',
        'Niasese' => 'Niasese',
        'Pekingese' => 'Pekingese',
        'Piedmontese' => 'Piedmontese',
        'pincers' => 'pincers',
        'Pistoiese' => 'Pistoiese',
        'pliers' => 'pliers',
        'Portuguese' => 'Portuguese',
        'proceedings' => 'proceedings',
        'rabies' => 'rabies',
        'rice' => 'rice',
        'rhinoceros' => 'rhinoceros',
        'salmon' => 'salmon',
        'Sarawakese' => 'Sarawakese',
        'scissors' => 'scissors',
        'series' => 'series',
        'Shavese' => 'Shavese',
        'shears' => 'shears',
        'siemens' => 'siemens',
        'species' => 'species',
        'swine' => 'swine',
        'testes' => 'testes',
        'trousers' => 'trousers',
        'trout' => 'trout',
        'tuna' => 'tuna',
        'Vermontese' => 'Vermontese',
        'Wenchowese' => 'Wenchowese',
        'whiting' => 'whiting',
        'wildebeest' => 'wildebeest',
        'Yengeese' => 'Yengeese',
    );

    /**
     * @var array map of special chars and its translation. This is used by [[slug()]].
     */
    public static $transliteration = array(
        // Numeric characters
        '??' => 1,
        '??' => 2,
        '??' => 3,

        // Latin
        '??' => 0,
        '??' => 'ae',
        '??' => 'ae',
        '??' => 'A',
        '??' => 'A',
        '??' => 'A',
        '??' => 'A',
        '??' => 'A',
        '??' => 'A',
        '??' => 'A',
        '??' => 'A',
        '??' => 'AE',
        '??' => 'AE',
        '??' => 'a',
        '??' => 'a',
        '??' => 'a',
        '??' => 'a',
        '??' => 'a',
        '??' => 'a',
        '??' => 'a',
        '??' => 'a',
        '??' => 'a',
        '@' => 'at',
        '??' => 'C',
        '??' => 'C',
        '??' => 'c',
        '??' => 'c',
        '??' => 'c',
        '??' => 'Dj',
        '??' => 'D',
        '??' => 'dj',
        '??' => 'd',
        '??' => 'E',
        '??' => 'E',
        '??' => 'E',
        '??' => 'E',
        '??' => 'E',
        '??' => 'E',
        '??' => 'e',
        '??' => 'e',
        '??' => 'e',
        '??' => 'e',
        '??' => 'e',
        '??' => 'e',
        '??' => 'f',
        '??' => 'G',
        '??' => 'G',
        '??' => 'g',
        '??' => 'g',
        '??' => 'H',
        '??' => 'H',
        '??' => 'h',
        '??' => 'h',
        '??' => 'I',
        '??' => 'I',
        '??' => 'I',
        '??' => 'I',
        '??' => 'I',
        '??' => 'I',
        '??' => 'I',
        '??' => 'I',
        '??' => 'IJ',
        '??' => 'i',
        '??' => 'i',
        '??' => 'i',
        '??' => 'i',
        '??' => 'i',
        '??' => 'i',
        '??' => 'i',
        '??' => 'i',
        '??' => 'ij',
        '??' => 'J',
        '??' => 'j',
        '??' => 'L',
        '??' => 'L',
        '??' => 'L',
        '??' => 'l',
        '??' => 'l',
        '??' => 'l',
        '??' => 'N',
        '??' => 'n',
        '??' => 'n',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'O',
        '??' => 'OE',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'o',
        '??' => 'oe',
        '??' => 'R',
        '??' => 'R',
        '??' => 'r',
        '??' => 'r',
        '??' => 'S',
        '??' => 'S',
        '??' => 's',
        '??' => 's',
        '??' => 's',
        '??' => 'T',
        '??' => 'T',
        '??' => 'T',
        '??' => 'TH',
        '??' => 't',
        '??' => 't',
        '??' => 't',
        '??' => 'th',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'U',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'u',
        '??' => 'W',
        '??' => 'w',
        '??' => 'Y',
        '??' => 'Y',
        '??' => 'Y',
        '??' => 'y',
        '??' => 'y',
        '??' => 'y',

        // Russian
        '??' => '',
        '??' => '',
        '??' => 'A',
        '??' => 'B',
        '??' => 'C',
        '??' => 'Ch',
        '??' => 'D',
        '??' => 'E',
        '??' => 'E',
        '??' => 'E',
        '??' => 'F',
        '??' => 'G',
        '??' => 'H',
        '??' => 'I',
        '??' => 'J',
        '??' => 'Ja',
        '??' => 'Ju',
        '??' => 'K',
        '??' => 'L',
        '??' => 'M',
        '??' => 'N',
        '??' => 'O',
        '??' => 'P',
        '??' => 'R',
        '??' => 'S',
        '??' => 'Sh',
        '??' => 'Shch',
        '??' => 'T',
        '??' => 'U',
        '??' => 'V',
        '??' => 'Y',
        '??' => 'Z',
        '??' => 'Zh',
        '??' => '',
        '??' => '',
        '??' => 'a',
        '??' => 'b',
        '??' => 'c',
        '??' => 'ch',
        '??' => 'd',
        '??' => 'e',
        '??' => 'e',
        '??' => 'e',
        '??' => 'f',
        '??' => 'g',
        '??' => 'h',
        '??' => 'i',
        '??' => 'j',
        '??' => 'ja',
        '??' => 'ju',
        '??' => 'k',
        '??' => 'l',
        '??' => 'm',
        '??' => 'n',
        '??' => 'o',
        '??' => 'p',
        '??' => 'r',
        '??' => 's',
        '??' => 'sh',
        '??' => 'shch',
        '??' => 't',
        '??' => 'u',
        '??' => 'v',
        '??' => 'y',
        '??' => 'z',
        '??' => 'zh',

        // German characters
        '??' => 'AE',
        '??' => 'OE',
        '??' => 'UE',
        '??' => 'ss',
        '??' => 'ae',
        '??' => 'oe',
        '??' => 'ue',

        // Turkish characters
        '??' => 'C',
        '??' => 'G',
        '??' => 'I',
        '??' => 'S',
        '??' => 'c',
        '??' => 'g',
        '??' => 'i',
        '??' => 's',

        // Latvian
        '??' => 'A',
        '??' => 'E',
        '??' => 'G',
        '??' => 'I',
        '??' => 'K',
        '??' => 'L',
        '??' => 'N',
        '??' => 'U',
        '??' => 'a',
        '??' => 'e',
        '??' => 'g',
        '??' => 'i',
        '??' => 'k',
        '??' => 'l',
        '??' => 'n',
        '??' => 'u',

        // Ukrainian
        '??' => 'G',
        '??' => 'I',
        '??' => 'Ji',
        '??' => 'Ye',
        '??' => 'g',
        '??' => 'i',
        '??' => 'ji',
        '??' => 'ye',

        // Czech
        '??' => 'C',
        '??' => 'D',
        '??' => 'E',
        '??' => 'N',
        '??' => 'R',
        '??' => 'S',
        '??' => 'T',
        '??' => 'U',
        '??' => 'Z',
        '??' => 'c',
        '??' => 'd',
        '??' => 'e',
        '??' => 'n',
        '??' => 'r',
        '??' => 's',
        '??' => 't',
        '??' => 'u',
        '??' => 'z',

        // Polish
        '??' => 'A',
        '??' => 'C',
        '??' => 'E',
        '??' => 'L',
        '??' => 'N',
        '??' => 'O',
        '??' => 'S',
        '??' => 'Z',
        '??' => 'Z',
        '??' => 'a',
        '??' => 'c',
        '??' => 'e',
        '??' => 'l',
        '??' => 'n',
        '??' => 'o',
        '??' => 's',
        '??' => 'z',
        '??' => 'z',

        // Greek
        '??' => 'A',
        '??' => 'B',
        '??' => 'G',
        '??' => 'D',
        '??' => 'E',
        '??' => 'Z',
        '??' => 'E',
        '??' => 'Th',
        '??' => 'I',
        '??' => 'K',
        '??' => 'L',
        '??' => 'M',
        '??' => 'N',
        '??' => 'X',
        '??' => 'O',
        '??' => 'P',
        '??' => 'R',
        '??' => 'S',
        '??' => 'T',
        '??' => 'Y',
        '??' => 'Ph',
        '??' => 'Ch',
        '??' => 'Ps',
        '??' => 'O',
        '??' => 'I',
        '??' => 'Y',
        '??' => 'a',
        '??' => 'e',
        '??' => 'e',
        '??' => 'i',
        '??' => 'Y',
        '??' => 'a',
        '??' => 'b',
        '??' => 'g',
        '??' => 'd',
        '??' => 'e',
        '??' => 'z',
        '??' => 'e',
        '??' => 'th',
        '??' => 'i',
        '??' => 'k',
        '??' => 'l',
        '??' => 'm',
        '??' => 'n',
        '??' => 'x',
        '??' => 'o',
        '??' => 'p',
        '??' => 'r',
        '??' => 's',
        '??' => 's',
        '??' => 't',
        '??' => 'y',
        '??' => 'ph',
        '??' => 'ch',
        '??' => 'ps',
        '??' => 'o',
        '??' => 'i',
        '??' => 'y',
        '??' => 'o',
        '??' => 'y',
        '??' => 'o',
        '??' => 'b',
        '??' => 'th',
        '??' => 'Y',

        /* Arabic */
        '??' => 'a',
        '??' => 'b',
        '??' => 't',
        '??' => 'th',
        '??' => 'g',
        '??' => 'h',
        '??' => 'kh',
        '??' => 'd',
        '??' => 'th',
        '??' => 'r',
        '??' => 'z',
        '??' => 's',
        '??' => 'sh',
        '??' => 's',
        '??' => 'd',
        '??' => 't',
        '??' => 'th',
        '??' => 'aa',
        '??' => 'gh',
        '??' => 'f',
        '??' => 'k',
        '??' => 'k',
        '??' => 'l',
        '??' => 'm',
        '??' => 'n',
        '??' => 'h',
        '??' => 'o',
        '??' => 'y',

        /* Vietnamese */
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'a',
        '???' => 'e',
        '???' => 'e',
        '???' => 'e',
        '???' => 'e',
        '???' => 'e',
        '???' => 'e',
        '???' => 'e',
        '???' => 'e',
        '???' => 'i',
        '???' => 'i',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'o',
        '???' => 'u',
        '???' => 'u',
        '???' => 'u',
        '???' => 'u',
        '???' => 'u',
        '???' => 'u',
        '???' => 'u',
        '???' => 'y',
        '???' => 'y',
        '???' => 'y',
        '???' => 'y',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'A',
        '???' => 'E',
        '???' => 'E',
        '???' => 'E',
        '???' => 'E',
        '???' => 'E',
        '???' => 'E',
        '???' => 'E',
        '???' => 'E',
        '???' => 'I',
        '???' => 'I',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'O',
        '???' => 'U',
        '???' => 'U',
        '???' => 'U',
        '???' => 'U',
        '???' => 'U',
        '???' => 'U',
        '???' => 'U',
        '???' => 'Y',
        '???' => 'Y',
        '???' => 'Y',
        '???' => 'Y',

        // burmese consonants
        '???' => 'k',
        '???' => 'kh',
        '???' => 'g',
        '???' => 'ga',
        '???' => 'ng',
        '???' => 's',
        '???' => 'sa',
        '???' => 'z',
        '??????' => 'za',
        '???' => 'ny',
        '???' => 't',
        '???' => 'ta',
        '???' => 'd',
        '???' => 'da',
        '???' => 'na',
        '???' => 't',
        '???' => 'ta',
        '???' => 'd',
        '???' => 'da',
        '???' => 'n',
        '???' => 'p',
        '???' => 'pa',
        '???' => 'b',
        '???' => 'ba',
        '???' => 'm',
        '???' => 'y',
        '???' => 'ya',
        '???' => 'l',
        '???' => 'w',
        '???' => 'th',
        '???' => 'h',
        '???' => 'la',
        '???' => 'a',
        // consonant character combos
        '???' => 'y',
        '???' => 'ya',
        '???' => 'w',
        '??????' => 'yw',
        '??????' => 'ywa',
        '???' => 'h',
        // independent vowels
        '???' => 'e',
        '???' => '-e',
        '???' => 'i',
        '???' => '-i',
        '???' => 'u',
        '???' => '-u',
        '???' => 'aw',
        '????????????' => 'aw',
        '???' => 'aw',
        '???' => 'ywae',
        '???' => 'hnaik',
        // numbers
        '???' => '0',
        '???' => '1',
        '???' => '2',
        '???' => '3',
        '???' => '4',
        '???' => '5',
        '???' => '6',
        '???' => '7',
        '???' => '8',
        '???' => '9',
        // virama and tone marks which are silent in transliteration
        '???' => '',
        '???' => '',
        '???' => '',
        // dependent vowels
        '???' => 'a',
        '???' => 'a',
        '???' => 'e',
        '???' => 'e',
        '???' => 'i',
        '???' => 'i',
        '??????' => 'o',
        '???' => 'u',
        '???' => 'u',
        '????????????' => 'aung',
        '??????' => 'aw',
        '?????????' => 'aw',
        '??????' => 'aw',
        '?????????' => 'aw',
        '???' => 'at',
        '??????' => 'et',
        '????????????' => 'aik',
        '????????????' => 'auk',
        '??????' => 'in',
        '????????????' => 'aing',
        '????????????' => 'aung',
        '??????' => 'it',
        '??????' => 'i',
        '??????' => 'at',
        '?????????' => 'eik',
        '?????????' => 'ok',
        '?????????' => 'ut',
        '?????????' => 'it',
        '??????' => 'd',
        '????????????' => 'ok',
        '?????????' => 'ait',
        '??????' => 'an',
        '?????????' => 'an',
        '?????????' => 'ein',
        '?????????' => 'on',
        '?????????' => 'un',
        '??????' => 'at',
        '?????????' => 'eik',
        '?????????' => 'ok',
        '?????????' => 'ut',
        '???????????????' => 'nub',
        '??????' => 'an',
        '?????????' => 'ein',
        '?????????' => 'on',
        '?????????' => 'un',
        '??????' => 'e',
        '????????????' => 'ol',
        '??????' => 'in',
        '???' => 'an',
        '??????' => 'ein',
        '??????' => 'on'
    );

    /**
     * Converts an underscored or CamelCase word into a English
     * sentence.
     * @param Str $words
     * @param boolean $ucAll whether to set all words to uppercase
     * @return Str
     */
    public static function titleize($words, $ucAll = false)
    {
        $words = self::humanize(self::underscore($words), $ucAll);

        return $ucAll ? ucwords($words) : ucfirst($words);
    }

    /**
     * Returns a human-readable string from $word
     * @param Str $word the string to humanize
     * @param boolean $ucAll whether to set all words to uppercase or not
     * @return Str
     */
    public static function humanize($word, $ucAll = false)
    {
        $word = str_replace('_', ' ', preg_replace('/_id$/', '', $word));

        return $ucAll ? ucwords($word) : ucfirst($word);
    }

    /**
     * Converts any "CamelCased" into an "underscored_word".
     * @param Str $words the word(s) to underscore
     * @return Str
     */
    public static function underscore($words)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $words));
    }

    /**
     * Converts a CamelCase name into space-separated words.
     * For example, 'PostTag' will be converted to 'Post Tag'.
     * @param Str $name the string to be converted
     * @param boolean $ucwords whether to capitalize the first letter in each word
     * @return Str the resulting words
     */
    public static function camel2words($name, $ucwords = true)
    {
        $label = trim(strtolower(str_replace(array(
            '-',
            '_',
            '.'
        ), ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name))));

        return $ucwords ? ucwords($label) : $label;
    }

    /**
     * Converts a CamelCase name into an ID in lowercase.
     * Words in the ID may be concatenated using the specified character (defaults to '-').
     * For example, 'PostTag' will be converted to 'post-tag'.
     * @param Str $name the string to be converted
     * @param Str $separator the character used to concatenate the words in the ID
     * @return Str the resulting ID
     */
    public static function camel2id($name, $separator = '-')
    {
        if ($separator === '_') {
            return trim(strtolower(preg_replace('/(?<![A-Z])[A-Z]/', '_\0', $name)), '_');
        } else {
            return trim(strtolower(str_replace('_', $separator, preg_replace('/(?<![A-Z])[A-Z]/', $separator . '\0', $name))), $separator);
        }
    }

    /**
     * Converts an ID into a CamelCase name.
     * Words in the ID separated by `$separator` (defaults to '-') will be concatenated into a CamelCase name.
     * For example, 'post-tag' is converted to 'PostTag'.
     * @param Str $id the ID to be converted
     * @param Str $separator the character used to separate the words in the ID
     * @return Str the resulting CamelCase name
     */
    public static function id2camel($id, $separator = '-')
    {
        return str_replace(' ', '', preg_replace('/\s(\d)/', '_${1}', ucwords(implode(' ', explode($separator, $id)))));
    }

    /**
     * Same as camelize but first char is in lowercase.
     * Converts a word like "send_email" to "sendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "whoSOnline"
     * @param Str $word to lowerCamelCase
     * @return Str
     */
    public static function variablize($word)
    {
        $word = self::camelize($word);

        return strtolower($word[0]) . substr($word, 1);
    }

    /**
     * Returns given word as CamelCased
     * Converts a word like "send_email" to "SendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "WhoSOnline"
     * @param Str $word the word to CamelCase
     * @return Str
     * @see variablize()
     */
    public static function camelize($word)
    {
        return str_replace(' ', '', ucwords(preg_replace('/[^A-Za-z0-9]+/', ' ', $word)));
    }

    /**
     * Converts a class name to its table name (pluralized)
     * naming conventions. For example, converts "Person" to "people"
     * @param Str $className the class name for getting related table_name
     * @return Str
     */
    public static function tableize($className)
    {
        return self::pluralize(self::underscore($className));
    }

    /**
     * Converts a word to its plural form.
     * Note that this is for English only!
     * For example, 'apple' will become 'apples', and 'child' will become 'children'.
     * @param Str $word the word to be pluralized
     * @return Str the pluralized word
     */
    public static function pluralize($word)
    {
        if (isset(self::$specials[$word])) {
            return self::$specials[$word];
        }
        foreach (self::$plurals as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }

        return $word;
    }

    /**
     * Returns a string with all spaces converted to given replacement and
     * non word characters removed.  Maps special characters to ASCII using
     * [[$transliteration]] array.
     * @param Str $string An arbitrary string to convert
     * @param Str $replacement The replacement to use for spaces
     * @param boolean $lowercase whether to return the string in lowercase or not. Defaults to `true`.
     * @return Str The converted string.
     */
    public static function slug($string, $replacement = '-', $lowercase = true)
    {
        $string = strip_tags(html_entity_decode(htmlspecialchars_decode($string)));
        $string = str_replace(array_keys(self::$transliteration), self::$transliteration, $string);
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $replacement, $string);
        $string = trim($string, $replacement);

        return $lowercase ? strtolower($string) : $string;
    }

    /**
     * Converts a table name to its class name. For example, converts "people" to "Person"
     * @param Str $tableName
     * @return Str
     */
    public static function classify($tableName)
    {
        return self::camelize(self::singularize($tableName));
    }

    /**
     * Returns the singular of the $word
     * @param Str $word the english word to singularize
     * @return Str Singular noun.
     */
    public static function singularize($word)
    {
        $result = array_search($word, self::$specials, true);
        if ($result !== false) {
            return $result;
        }
        foreach (self::$singulars as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }

        return $word;
    }

    /**
     * Converts number to its ordinal English form. For example, converts 13 to 13th, 2 to 2nd ...
     * @param integer $number the number to get its ordinal value
     * @return Str
     */
    public static function ordinalize($number)
    {
        if (in_array(($number % 100), range(11, 13))) {
            return $number . 'th';
        }
        switch ($number % 10) {
            case 1:
                return $number . 'st';
            case 2:
                return $number . 'nd';
            case 3:
                return $number . 'rd';
            default:
                return $number . 'th';
        }
    }
}
