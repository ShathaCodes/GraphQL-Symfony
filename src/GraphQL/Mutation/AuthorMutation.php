<?php

namespace App\GraphQL\Mutation;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class AuthorMutation implements MutationInterface, AliasedInterface
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createAuthor(Argument $args): Author
    {
        $rawargs = $args->getRawArguments();
        $author = new Author();
        foreach ($rawargs['input'] as $key => $value) {
            $setter = sprintf('set%s', $key);
            $author->$setter($value);
        }
        $this->em->persist($author);
        $this->em->flush();
        return $author;
    }

    public static function getAliases(): array
    {
        return [
            'createAuthor' => 'create_author'
        ];

    }
}
