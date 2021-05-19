<?php

namespace App\GraphQL\Resolver;

use App\Entity\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;


Class PostResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function resolve(Argument $args)
    {
            return $this->em->getRepository('App:Post')->find($args['id']);
    }
    public function findAll(Argument $args){
        return $this->em->getRepository(Post::class)->findBy(
            [],
            ['id' => 'asc'],
            $args['limit'],
            0
        );
    }
    public static function getAliases() : array
    {
        return [
            'resolve' => 'Post',
            'findAll' => 'all_posts'
        ];
    }
}
