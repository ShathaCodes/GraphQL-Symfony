<?php
namespace App\GraphQL\Mutation;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class PostMutation implements MutationInterface, AliasedInterface {

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function createPost(Argument $args): Post{
        $rawargs= $args->getRawArguments();
        $post=new Post();
        foreach ($rawargs['input'] as $key => $value){
            if ( $key == "author_id"){
                $author= $this->em->getRepository('App:Author')->find($value);
            }
            else{
            $setter = sprintf('set%s',$key);
            $post->$setter($value);}
        }
        $author->addPost($post);
            $this->em->persist($post);
            $this->em->flush();
        return $post ;
    }
    public function updatePost(Argument $args): Post{
        $rawargs= $args->getRawArguments();
        $post= $this->em->getRepository('App:Post')->find($args['id']);
        foreach ($rawargs['input'] as $key => $value){
            if ( $key == "author_id"){
                $author= $this->em->getRepository('App:Author')->find($value);
            }
            else{
                $setter = sprintf('set%s',$key);
                $post->$setter($value);}
        }
        $this->em->persist($post);
        $this->em->flush();
        return $post ;
    }
    public function deletePost(Argument $args){
        $post= $this->em->getRepository('App:Post')->find($args['id']);
        $author =$post->getAuthor();
        $author->removePost($post);
        $this->em->remove($post);
        $this->em->flush();
        return null;
    }
    public static function getAliases() : array
    {
        return [
            'createPost' => 'create_post',
            'updatePost' => 'update_post',
            'deletePost' => 'delete_post'
        ];
    }
}
