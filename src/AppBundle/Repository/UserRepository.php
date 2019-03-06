<?php

namespace AppBundle\Repository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\QueryBuilder;

use AppBundle\Entity\User;
use AppBundle\Entity\Job;
use AppBundle\Entity\Doctor;
use AppBundle\Entity\Clinic;
use AppBundle\Entity\DoctorSpecialization;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository implements UserLoaderInterface
{

	private $myCurrentParams;

	public function loadUserByUsername($username){
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->andWhere('u.state = :state')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->setParameter('state', "activate")
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function addWhereClause(&$qb,&$params){

        if(@$params["q"]){
            $params["name"] = $params["q"];
        }

        $params = array_filter($params,function($el){
            if(is_array($el)){
                return $el;
            }
            return strip_tags(trim($el));
        });

        // recherche par id
        if(@$params["id"]){
            $this->whereId($qb,@$params["id"]);
        }

        // recherche par terms
        if(@$params["name"]){
            $this->whereTerms($qb,@$params["name"]);
        }

        // recherche par doctorType
        if(@$params["doctorType"]){
            $this->whereDoctorType($qb,@$params["doctorType"]);
        }

        // recherche par job
        if(@$params["job"]){
            $this->whereJob($qb,@$params["job"]);
        }

        // recherche par city
        if(@$params["city"]){
            $this->whereCity($qb,@$params["city"]);
        }

        // recherche par specializations
        if(@$params["specializations"]){
            $this->whereSpecializations($qb,@$params["specializations"]);
        }

        // ordre d'affichage par id
        if(@$params['order_id']){
            $order = strtoupper(trim($params['order_id'])) == "ASC" ? "ASC" : "DESC";
            $qb->orderBy("m.id",$order);
        }

        if(!@$params['order_id'] && !@$params["order_name"]){
            $params["order_name"] = "asc";
        }

        // ordre d'affichage par nom de programme
        if(@$params['order_name']){
            $order = strtoupper(trim($params['order_name'])) == "ASC" ? "ASC" : "DESC";
            $qb->orderBy("m.name",$order);
        }

        // ordre d'affichage par date e production
        if(@$params['order_year']){
            $order = strtoupper(trim($params['order_year'])) == "ASC" ? "ASC" : "DESC";
            $qb->orderBy("m.yearStart",$order);
        }

        return $this;
    }

    public function search($params = array(),$limit = 20,$offset=0){
		$qb = $this->_em->createQueryBuilder();

        $this->myCurrentParams = $params;

		$qb->select("u")
		->from(User::class,"u")
		->leftJoin("u.country","country")
        ->leftJoin("u.city","city")
        ->leftJoin("u.userType","userType")
        ;

        $this->addWhereClause($qb,$params);

	    // limit et offset
        if($limit != -1){
            $qb
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );
        }

   		$query = $qb->getQuery();

       /* $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );*/

	    return $query->getResult();
	}


	public function whereId(QueryBuilder $qb,$value){
        if(is_array($value)){
            $qb->andWhere($qb->expr()->in("u.id",":id"));
        }
        else{
            $qb->andWhere($qb->expr()->eq("u.id",":id"));
        }
        $qb->setParameter("id",$value);
    }

	public function whereTerms(QueryBuilder $qb,$value){
        $terms_1 = $value;

        if(@$this->myCurrentParams['_search_mode'] == "google"){

        	$qb2->select("doc_spec_q.id")
			->from(DoctorSpecialization::class,"doc_spec_q")
			->innerJoin("doc_spec_q.user","user4")
			->innerJoin("doc_spec_q.specialization","spec")
			->where("user4.id = u.id")
			->andWhere("(MATCH_AGAINST(spec.name, :q) > 0)");
        	
            $qb->andWhere(
            	$qb->expr()->orX(
	                "(MATCH_AGAINST(u.username, :q) > 0)",
	                $qb->expr()->exists($qb2)
	            ))
            ->setParameter("q",$value);
        }
        else{
            $qb->andWhere(
                "u.username LIKE :q",
            )
            ->setParameter("q","%$value%");
        }
	}

    public function whereState(QueryBuilder $qb,$value){
        $qb->andWhere($qb->expr()->eq("u.state",":state"))
        ->setParameter("state",$value);
    }

    public function whereYearStart(QueryBuilder $qb,$value){
        $value = substr($value,0,4)."-01-01";
        $value = new \Datetime($value);

        $qb->andWhere(
            $qb->expr()->eq("DATE_FORMAT(u.createAt,'%Y')",":year_start")
        )
        ->setParameter("year_start",$value->format("Y"));
    }

   
	public function whereYearRange(QueryBuilder $qb,$start,$end){

        $start = substr($start,0,4)."-01-01";
        $start = new \Datetime($start);

        $end = substr($end,0,4)."-01-01";
        $end = new \Datetime($end);

		$qb->andWhere(
			$qb->expr()->between("DATE_FORMAT(u.createAt,'%Y')",":year_start",":year_end")
		)
        ->setParameter("year_start",$start->format("Y"))
        ->setParameter("year_end",$end->format("Y"));
  	}

  
	public function whereCity(QueryBuilder $qb,$value){
        $qb->andWhere("city.slug = :city")
		->setParameter("city",$value);
  	}

  	public function whereCountry(QueryBuilder $qb,$value){
        $qb->andWhere("country.slug = :country")
		->setParameter("country",$value);
  	}

  	public function whereUserType(QueryBuilder $qb,$value){
        $qb->andWhere("userType.slug = :user_type")
		->setParameter("user_type",$value);
  	}

  	public function whereJob(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

        $qb2->select("doctor.id")
		->from(Doctor::class,"doctor")
		->innerJoin("doctor.user","user")
		->innerJoin("doctor.job","job")
		->where("user.id = u.id");
        
        if(is_numeric($value)){
            $qb2->andWhere("job.id = :job");
        }
        else{
            $qb2->andWhere("job.slug = :job");
        }

        $qb->andWhere(
            $qb->expr()->exists($qb2)
        )
        ->setParameter("job",$value);
  	}

    public function whereDoctorType(QueryBuilder $qb,$value){
        $qb2 = $this->_em->createQueryBuilder();

        $qb2->select("doctor2.id")
		->from(Doctor::class,"doctor2")
		->innerJoin("doctor2.user","user2")
		->innerJoin("doctor2.doctorType","doctorType")
		->where("user2.id = u.id");
        
        if(is_numeric($value)){
            $qb2->andWhere("doctorType.id = :doctor_type");
        }
        else{
            $qb2->andWhere("doctorType.slug = :doctor_type");
        }

        $qb->andWhere(
            $qb->expr()->exists($qb2)
        )
        ->setParameter("doctor_type",$value);
    }


  	public function whereSpecializations(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

        $qb2->select("doc_spec.id")
		->from(DoctorSpecialization::class,"doc_spec")
		->innerJoin("doc_spec.user","user3")
		->innerJoin("doc_spec.specialization","specialization")
		->where("user3.id = u.id");
        
        if(is_numeric($value)){
            $qb2->andWhere("specialization.id = :specialization");
        }
        else{
            $qb2->andWhere("specialization.slug = :specialization");
        }

        $qb->andWhere(
            $qb->expr()->exists($qb2)
        )
        ->setParameter("specialization",$value);
  	}

    public function count(array $params = array() ){
        $qb = $this->createQueryBuilder('u');
        
        $qb
        ->leftJoin("u.country","country")
        ->leftJoin("u.city","city")
        ->leftJoin("u.userType","userType")
        ->select('count(u.id)');

        $this->addWhereClause($qb, $params);

        return $qb->getQuery()
        ->getSingleScalarResult();
    }
}
