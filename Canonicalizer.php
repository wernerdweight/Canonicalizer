<?php
declare(strict_types=1);

namespace WernerDweight\Canonicalizer;

class Canonicalizer
{
    /**
     * @var string[][]
     */
    private const TRANSLITERATION_MAP = [
        [
            // cyrilic
            'Щ', 'щ', 'Ё', 'Ж', 'Х', 'Ц', 'Ч', 'Ш', 'Ю', 'я', 'ё', 'ж', 'х', 'ц', 'ч', 'ш', 'ю', 'я', 'А', 'Б', 'В',
            'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ь', 'Ы', 'Ъ', 'Э',
            'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ь',
            'ы', 'ъ', 'э',
            // french
            'Ï', 'ï', 'Ÿ', 'ÿ', 'Ê', 'ê', 'À', 'à', 'È', 'è', 'Ù', 'ù', 'Û', 'û',
            // spanish
            'Ñ', 'ñ',
        ],
        [
            // cyrilic
            'Sc', 'sc', 'Jo', 'Z', 'Ch', 'C', 'C', 'S', 'Ju', 'ja', 'jo', 'z', 'ch', 'c', 'c', 's', 'ju', 'ja', 'A',
            'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', '', 'Y', '',
            'E', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f',
            '', 'y', '', 'e',
            // french
            'I', 'i', 'Y', 'y', 'E', 'e', 'A', 'a', 'E', 'e', 'U', 'u', 'U', 'u',
            //spanish
            'N', 'n',
        ],
    ];

    /**
     * @var string
     */
    private const UNICODE_SPECIAL_CHAR_BLACKLIST = '/[^\x09\x0A\x0D\x20-\x7E\xA0-\x{2FF}\x{370}-\x{10FFFF}]/u';

    /**
     * @var string[]
     */
    private const SPECIAL_CHAR_BLACKLIST = ['`', '\'', '"', '^', '~'];

    /**
     * @var string[]
     */
    private const GLIBC_W1250_CHAR_MAP = [
        "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4" .
        "\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde" .
        "\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9" .
        "\xfa\xfb\xfc\xfd\xfe\x96",
        'ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt-',
    ];

    /**
     * @var string
     */
    private const DEFAULT_SEPARATOR = '-';

    /**
     * @var string
     */
    private const DEFAULT_ENDING = '';

    /**
     * @var null|callable(string):string
     */
    private $afterCallback;

    /**
     * @var null|callable(string):string
     */
    private $beforeCallback;

    /**
     * @var int
     */
    private $maxLength;

    /**
     * @param callable(string):string|null $beforeCallback
     * @param callable(string):string|null $afterCallback
     */
    public function __construct(int $maxLength, ?callable $beforeCallback = null, ?callable $afterCallback = null)
    {
        $this->maxLength = $maxLength;
        $this->beforeCallback = $beforeCallback;
        $this->afterCallback = $afterCallback;
    }

    /**
     * @param callable(string):string|null $beforeCallback
     */
    public function setBeforeCallback(?callable $beforeCallback): void
    {
        $this->beforeCallback = $beforeCallback;
    }

    /**
     * @param callable(string):string|null $afterCallback
     */
    public function setAfterCallback(?callable $afterCallback): void
    {
        $this->afterCallback = $afterCallback;
    }

    public function canonicalize(
        string $string,
        string $ending = self::DEFAULT_ENDING,
        string $separator = self::DEFAULT_SEPARATOR
    ): string {
        if (null !== $this->beforeCallback) {
            $beforeCallback = $this->beforeCallback;
            $string = $beforeCallback($string);
        }
        $string = $this->toAscii($string);
        $string = mb_strtolower($string);
        /** @var string $string */
        $string = \Safe\preg_replace('/[^a-z0-9]+/i', $separator, $string);
        $string = trim($string, $separator);
        if (self::DEFAULT_ENDING !== $ending) {
            $string = $this->createEnding($string, $ending, $separator);
        }
        if (null !== $this->afterCallback) {
            $afterCallback = $this->afterCallback;
            $string = $afterCallback($string);
        }
        return $string;
    }

    private function toAscii(string $string): string
    {
        // transliterate cyrilic and other special chars
        $string = str_replace(self::TRANSLITERATION_MAP[0], self::TRANSLITERATION_MAP[1], $string);
        // get rid of some unicode special chars like tabs etc.
        /** @var string $string */
        $string = \Safe\preg_replace(self::UNICODE_SPECIAL_CHAR_BLACKLIST, '', $string);
        // get rid of some special chars
        $string = str_replace(self::SPECIAL_CHAR_BLACKLIST, '', $string);
        // transliterate to ASCII/Win-1250
        if (ICONV_IMPL === 'glibc') {
            $string = \Safe\iconv('UTF-8', 'WINDOWS-1250//TRANSLIT//IGNORE', $string);
            $string = strtr($string, ...self::GLIBC_W1250_CHAR_MAP);
            return $string;
        }
        $string = \Safe\iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        // get rid of some special chars (again, since iconv implementations other than glibc might put some back in)
        $string = str_replace(self::SPECIAL_CHAR_BLACKLIST, '', $string);
        return $string;
    }

    private function createEnding(string $string, string $ending, string $separator): string
    {
        $ending = $separator . trim($ending, $separator);
        $maxLength = $this->maxLength - mb_strlen($ending);
        if (mb_strlen($string) > $maxLength) {
            $substring = mb_substr($string, 0, $maxLength);
            $string = trim($substring, $separator);
        }
        return sprintf('%s%s', $string, $ending);
    }
}
