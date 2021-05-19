<?php

namespace App\GraphQL\Resolver;

use App\Entity\Author;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use PhpParser\Node\Arg;

class AuthorResolver implements ResolverInterface, AliasedInterface
{
    private $em;
    /**
     * AuthorResolver constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function resolve(Argument $args)
    {
        $author = $this->em->getRepository(Author::class)->find($args['id']);
        return $author;
    }
    public function findAll(){
        return $this->em->getRepository(Author::class)->findAll();
    }
    public static function getAliases(): array
    {
        return [
            'resolve' => 'Author',
            'findAll'=>"all_authors"
        ];
    }
}
