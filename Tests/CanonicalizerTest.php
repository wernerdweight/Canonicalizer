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
     * @return Canonicalizer
     */
    protected function getCanonicalizer(): Canonicalizer
    {
        if (null === $this->canonicalizer) {
            $this->canonicalizer = new Canonicalizer(255);
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
            $this->getCanonicalizer()->canonicalize('Some fancy text with 2 numbers or whatever...')
        );
    }

    /**
     * Test canonicalization.
     */
    public function testCanonicalize(): void
    {
        $this->assertEquals(
            'priserne-zlutoucky-kun-upel-dabelske-ody',
            $this->getCanonicalizer()->canonicalize('Příšerně žluťoučký kůň úpěl ďábelské ódy')
        );
        $this->assertEquals(
            'a-quick-brown-fox-jumps-over-the-lazy-dog',
            $this->getCanonicalizer()->canonicalize('A quick brown fox jumps over the lazy dog')
        );
        $this->assertContains(
            $this->getCanonicalizer()->canonicalize('Falsches Üben von Xylophonmusik quält jeden größeren Zwerg'),
            [
                'falsches-uben-von-xylophonmusik-qualt-jeden-grosseren-zwerg',
                'falsches-uben-von-xylophonmusik-qualt-jeden-groseren-zwerg',
            ]
        );
        $this->assertContains(
            $this->getCanonicalizer()->canonicalize(
                'Le cœur déçu mais l\'âme plutôt naïve, Louÿs rêva de crapaüter en canoë au delà des îles, près du mälström où brûlent les novæ.'
            ),
            [
                'le-coeur-decu-mais-l-ame-plutot-naive-louys-reva-de-crapauter-en-canoe-au-dela-des-iles-pres-du-malstrom-ou-brulent-les-novae',
                'le-coeur-decu-mais-lame-plutot-naive-louys-reva-de-crapauter-en-canoe-au-dela-des-iles-pres-du-malstrom-ou-brulent-les-novae',
            ]
        );
        $this->assertEquals(
            'krdel-stastnych-datlov-uci-pri-usti-vahu-mlkveho-kona-obhryzat-koru-a-zrat-cerstve-maso',
            $this->getCanonicalizer()->canonicalize(
                'Kŕdeľ šťastných ďatľov učí pri ústí Váhu mĺkveho koňa obhrýzať kôru a žrať čerstvé mäso.'
            )
        );
        $this->assertEquals(
            'quel-fez-sghembo-copre-davanti',
            $this->getCanonicalizer()->canonicalize('Quel fez sghembo copre davanti')
        );
        $this->assertEquals(
            'pchnac-w-te-lodz-jeza-lub-osm-skrzyn-fig',
            $this->getCanonicalizer()->canonicalize('Pchnąć w tę łódź jeża lub ośm skrzyń fig')
        );
        $this->assertEquals(
            'v-cascach-juga-zil-by-citrus-da-no-falsivyj-ekzempljar',
            $this->getCanonicalizer()->canonicalize(
                'В чащах юга жил бы цитрус? Да, но фальшивый экземпляр!'
            )
        );
    }
}
