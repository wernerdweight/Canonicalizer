<?php
declare(strict_types=1);

namespace WernerDweight\Canonicalizer\Tests;

use PHPUnit\Framework\TestCase;
use WernerDweight\Canonicalizer\Canonicalizer;

/**
 * Canonicalizer tests.
 */
class CanonicalizerTest extends TestCase
{
    /** @var Canonicalizer */
    protected $canonicalizer;

    /**
     * @param int           $maxLength
     * @param callable|null $beforeCallback
     * @param callable|null $afterCallback
     *
     * @return Canonicalizer
     */
    protected function getCanonicalizer(
        int $maxLength,
        ?callable $beforeCallback = null,
        ?callable $afterCallback = null
    ): Canonicalizer {
        if (null === $this->canonicalizer) {
            $this->canonicalizer = new Canonicalizer($maxLength, $beforeCallback, $afterCallback);
        }
        return $this->canonicalizer;
    }

    /**
     * Test return type.
     */
    public function testReturnType(): void
    {
        $this->assertInternalType(
            'string',
            $this->getCanonicalizer(255)->canonicalize('Some fancy text with 2 numbers or whatever...')
        );
    }

    /**
     * Test canonicalization.
     */
    public function testCanonicalize(): void
    {
        $this->assertEquals(
            'priserne-zlutoucky-kun-upel-dabelske-ody',
            $this->getCanonicalizer(255)->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
        $this->assertEquals(
            'a-quick-brown-fox-jumps-over-the-lazy-dog',
            $this->getCanonicalizer(255)->canonicalize('A quick brown fox jumps over the lazy dog')
        );
        $this->assertContains(
            $this->getCanonicalizer(255)->canonicalize(
                'Falsches Üben von Xylophonmusik quält jeden größeren Zwerg'
            ),
            [
                'falsches-uben-von-xylophonmusik-qualt-jeden-grosseren-zwerg',
                'falsches-uben-von-xylophonmusik-qualt-jeden-groseren-zwerg',
            ]
        );
        $this->assertContains(
            $this->getCanonicalizer(255)->canonicalize(
                'Le cœur déçu mais l\'âme plutôt naïve, Louÿs rêva de crapaüter en canoë au delà des îles, près du mälström où brûlent les novæ.'
            ),
            [
                'le-coeur-decu-mais-l-ame-plutot-naive-louys-reva-de-crapauter-en-canoe-au-dela-des-iles-pres-du-malstrom-ou-brulent-les-novae',
                'le-coeur-decu-mais-lame-plutot-naive-louys-reva-de-crapauter-en-canoe-au-dela-des-iles-pres-du-malstrom-ou-brulent-les-novae',
            ]
        );
        $this->assertEquals(
            'krdel-stastnych-datlov-uci-pri-usti-vahu-mlkveho-kona-obhryzat-koru-a-zrat-cerstve-maso',
            $this->getCanonicalizer(255)->canonicalize(
                'Kŕdeľ šťastných ďatľov učí pri ústí Váhu mĺkveho koňa obhrýzať kôru a žrať čerstvé mäso.'
            )
        );
        $this->assertEquals(
            'quel-fez-sghembo-copre-davanti',
            $this->getCanonicalizer(255)->canonicalize('Quel fez sghembo copre davanti')
        );
        $this->assertEquals(
            'pchnac-w-te-lodz-jeza-lub-osm-skrzyn-fig',
            $this->getCanonicalizer(255)->canonicalize('Pchnąć w tę łódź jeża lub ośm skrzyń fig')
        );
        $this->assertEquals(
            'v-cascach-juga-zil-by-citrus-da-no-falsivyj-ekzempljar',
            $this->getCanonicalizer(255)->canonicalize(
                'В чащах юга жил бы цитрус? Да, но фальшивый экземпляр!'
            )
        );
        $this->assertEquals(
            'el-veloz-murcielago-hindu-comia-feliz-cardillo-y-kiwi-la-ciguena-tocaba-el-saxofon-detras-del-palenque-de-paja',
            $this->getCanonicalizer(255)->canonicalize(
                'El veloz murciélago hindú comía feliz cardillo y kiwi. La cigüeña tocaba el saxofón detrás del palenque de paja'
            )
        );
    }

    /**
     * Test ending.
     */
    public function testEnding(): void
    {
        $this->assertEquals(
            'priserne-zlutoucky-kun-upel-dabelske-ody-ending',
            $this->getCanonicalizer(80)->canonicalize(
                'Příšerně žluťoučký kůň úpěl ďábelské ódy',
                '-ending'
            )
        );
        $this->assertEquals(
            'a-quick-brown-fox-jumps-over-the-lazy-dog-ending',
            $this->getCanonicalizer(80)->canonicalize('A quick brown fox jumps over the lazy dog', '-ending')
        );
        $this->assertContains(
            $this->getCanonicalizer(80)->canonicalize(
                'Falsches Üben von Xylophonmusik quält jeden größeren Zwerg',
                '-ending'
            ),
            [
                'falsches-uben-von-xylophonmusik-qualt-jeden-grosseren-zwerg-ending',
                'falsches-uben-von-xylophonmusik-qualt-jeden-groseren-zwerg-ending',
            ]
        );
        $this->assertContains(
            $this->getCanonicalizer(80)->canonicalize(
                'Le cœur déçu mais l\'âme plutôt naïve, Louÿs rêva de crapaüter en canoë au delà des îles, près du mälström où brûlent les novæ.',
                '-ending'
            ),
            [
                'le-coeur-decu-mais-l-ame-plutot-naive-louys-reva-de-crapauter-en-canoe-au-ending',
                'le-coeur-decu-mais-lame-plutot-naive-louys-reva-de-crapauter-en-canoe-au-ending',
            ]
        );
        $this->assertEquals(
            'krdel-stastnych-datlov-uci-pri-usti-vahu-mlkveho-kona-obhryzat-koru-a-zra-ending',
            $this->getCanonicalizer(80)->canonicalize(
                'Kŕdeľ šťastných ďatľov učí pri ústí Váhu mĺkveho koňa obhrýzať kôru a žrať čerstvé mäso.',
                '-ending'
            )
        );
        $this->assertEquals(
            'quel-fez-sghembo-copre-davanti-ending',
            $this->getCanonicalizer(80)->canonicalize('Quel fez sghembo copre davanti', '-ending')
        );
        $this->assertEquals(
            'pchnac-w-te-lodz-jeza-lub-osm-skrzyn-fig-ending',
            $this->getCanonicalizer(80)->canonicalize('Pchnąć w tę łódź jeża lub ośm skrzyń fig', '-ending')
        );
        $this->assertEquals(
            'v-cascach-juga-zil-by-citrus-da-no-falsivyj-ekzempljar-ending',
            $this->getCanonicalizer(80)->canonicalize(
                'В чащах юга жил бы цитрус? Да, но фальшивый экземпляр!',
                '-ending'
            )
        );
    }

    /**
     * Test separator.
     */
    public function testSeparator(): void
    {
        $this->assertEquals(
            'priserne zlutoucky kun upel dabelske ody',
            $this->getCanonicalizer(255)->canonicalize(
                'Příšerně žluťoučký kůň úpěl ďábelské ódy',
                '',
                ' '
            )
        );
        $this->assertEquals(
            'a quick brown fox jumps over the lazy dog',
            $this->getCanonicalizer(255)->canonicalize('A quick brown fox jumps over the lazy dog', '', ' ')
        );
        $this->assertContains(
            $this->getCanonicalizer(255)->canonicalize(
                'Falsches Üben von Xylophonmusik quält jeden größeren Zwerg',
                '',
                ' '
            ),
            [
                'falsches uben von xylophonmusik qualt jeden grosseren zwerg',
                'falsches uben von xylophonmusik qualt jeden groseren zwerg',
            ]
        );
        $this->assertContains(
            $this->getCanonicalizer(255)->canonicalize(
                'Le cœur déçu mais l\'âme plutôt naïve, Louÿs rêva de crapaüter en canoë au delà des îles, près du mälström où brûlent les novæ.',
                '',
                ' '
            ),
            [
                'le coeur decu mais l ame plutot naive louys reva de crapauter en canoe au dela des iles pres du malstrom ou brulent les novae',
                'le coeur decu mais lame plutot naive louys reva de crapauter en canoe au dela des iles pres du malstrom ou brulent les novae',
            ]
        );
        $this->assertEquals(
            'krdel stastnych datlov uci pri usti vahu mlkveho kona obhryzat koru a zrat cerstve maso',
            $this->getCanonicalizer(255)->canonicalize(
                'Kŕdeľ šťastných ďatľov učí pri ústí Váhu mĺkveho koňa obhrýzať kôru a žrať čerstvé mäso.',
                '',
                ' '
            )
        );
        $this->assertEquals(
            'quel fez sghembo copre davanti',
            $this->getCanonicalizer(255)->canonicalize('Quel fez sghembo copre davanti', '', ' ')
        );
        $this->assertEquals(
            'pchnac w te lodz jeza lub osm skrzyn fig',
            $this->getCanonicalizer(255)->canonicalize('Pchnąć w tę łódź jeża lub ośm skrzyń fig', '', ' ')
        );
        $this->assertEquals(
            'v cascach juga zil by citrus da no falsivyj ekzempljar',
            $this->getCanonicalizer(255)->canonicalize(
                'В чащах юга жил бы цитрус? Да, но фальшивый экземпляр!',
                '',
                ' '
            )
        );
    }

    /**
     * Test callbacks.
     */
    public function testCallbacks(): void
    {
        $this->assertEquals(
            'PRISARNE-ZLUTOUCKY-KUN-UPEL-DABALSKE-ODY',
            $this->getCanonicalizer(
                255,
                function (string $string): string {
                    return str_replace(['a', 'e'], ['e', 'a'], $string);
                },
                'strtoupper'
            )->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
        $this->assertEquals(
            'A-QUICK-BROWN-FOX-JUMPS-OVAR-THA-LAZY-DOG',
            $this->getCanonicalizer(255)->canonicalize('A quick brown fox jumps over the lazy dog')
        );
        $this->assertContains(
            $this->getCanonicalizer(255)->canonicalize(
                'Falsches Üben von Xylophonmusik quält jeden größeren Zwerg'
            ),
            [
                'FALSCHAS-UBAN-VON-XYLOPHONMUSIK-QUALT-JADAN-GROSSARAN-ZWARG',
                'FALSCHAS-UBAN-VON-XYLOPHONMUSIK-QUALT-JADAN-GROSARAN-ZWARG',
            ]
        );
        $this->assertContains(
            $this->getCanonicalizer(255)->canonicalize(
                'Le cœur déçu mais l\'âme plutôt naïve, Louÿs rêva de crapaüter en canoë au delà des îles, près du mälström où brûlent les novæ.'
            ),
            [
                'LA-COEUR-DECU-MAIS-L-AMA-PLUTOT-NAIVA-LOUYS-REVA-DA-CRAPAUTAR-AN-CANOE-AU-DALA-DAS-ILAS-PRES-DU-MALSTROM-OU-BRULANT-LAS-NOVAE',
                'LA-COEUR-DECU-MAIS-LAMA-PLUTOT-NAIVA-LOUYS-REVA-DA-CRAPAUTAR-AN-CANOE-AU-DALA-DAS-ILAS-PRES-DU-MALSTROM-OU-BRULANT-LAS-NOVAE',
            ]
        );
        $this->assertEquals(
            'KRDAL-STASTNYCH-DATLOV-UCI-PRI-USTI-VAHU-MLKVAHO-KONA-OBHRYZAT-KORU-A-ZRAT-CARSTVE-MASO',
            $this->getCanonicalizer(255)->canonicalize(
                'Kŕdeľ šťastných ďatľov učí pri ústí Váhu mĺkveho koňa obhrýzať kôru a žrať čerstvé mäso.'
            )
        );
        $this->assertEquals(
            'QUAL-FAZ-SGHAMBO-COPRA-DAVANTI',
            $this->getCanonicalizer(255)->canonicalize('Quel fez sghembo copre davanti')
        );
        $this->assertEquals(
            'PCHNAC-W-TE-LODZ-JAZA-LUB-OSM-SKRZYN-FIG',
            $this->getCanonicalizer(255)->canonicalize('Pchnąć w tę łódź jeża lub ośm skrzyń fig')
        );
        $this->assertEquals(
            'V-CASCACH-JUGA-ZIL-BY-CITRUS-DA-NO-FALSIVYJ-EKZEMPLJAR',
            $this->getCanonicalizer(255)->canonicalize(
                'В чащах юга жил бы цитрус? Да, но фальшивый экземпляр!'
            )
        );
    }

    /**
     * Test callback setters.
     */
    public function testCallbackSetterss(): void
    {
        $canonicalizer = $this->getCanonicalizer(255);
        $this->assertEquals(
            'priserne-zlutoucky-kun-upel-dabelske-ody',
            $canonicalizer->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
        $canonicalizer->setBeforeCallback(function (string $string): string {
            return str_replace(['a', 'e'], ['e', 'a'], $string);
        });
        $this->assertEquals(
            'prisarne-zlutoucky-kun-upel-dabalske-ody',
            $canonicalizer->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
        $canonicalizer->setAfterCallback('strtoupper');
        $this->assertEquals(
            'PRISARNE-ZLUTOUCKY-KUN-UPEL-DABALSKE-ODY',
            $canonicalizer->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
        $canonicalizer->setBeforeCallback(null);
        $this->assertEquals(
            'PRISERNE-ZLUTOUCKY-KUN-UPEL-DABELSKE-ODY',
            $canonicalizer->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
        $canonicalizer->setAfterCallback(null);
        $this->assertEquals(
            'priserne-zlutoucky-kun-upel-dabelske-ody',
            $canonicalizer->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
    }
}
