<?php
namespace App\GraphQL\Mutation;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class UserMutation implements MutationInterface, AliasedInterface {

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function createUser(Argument $args): User{
        $rawargs= $args->getRawArguments();
        $user= new User();
        foreach ($rawargs['input'] as $key => $value){
            $setter = sprintf('set%s',$key);
            $user->$setter($value);
        }
        $this->em->persist($user);
        $this->em->flush();
        return $user ;
    }
    public function updateUser(Argument $args): User{
        $rawargs= $args->getRawArguments();
        $user= $this->em->getRepository('App:User')->find($rawargs['id']);
        foreach ($rawargs['input'] as $key => $value){
            $setter = sprintf('set%s',$key);
            $user->$setter($value);
        }
        $this->em->persist($user);
        $this->em->flush();
        return $user ;
    }
    public function deleteUser(Argument $args){
        $user= $this->em->getRepository('App:User')->find($args['id']);
        $this->em->remove($user);
        $this->em->flush();
        return null;
    }
    public static function getAliases() : array
    {
        return [
            'createUser' => 'create_user',
            'updateUser' => 'update_user',
            'deleteUser' => 'delete_user'

        ];

    }
}

