<?php

namespace AppBundle\Repository;
use Doctrine\ORM\QueryBuilder;

use AppBundle\Entity\DoctorSpecialization;
use AppBundle\Entity\Doctor;

/**
 * DoctorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DoctorRepository extends \Doctrine\ORM\EntityRepository
{
	private $myCurrentParams;

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
        if(@$params["grade"]){
            $this->whereGrade($qb,@$params["grade"]);
        }

        // recherche par user_slug
        if(@$params["slug"]){
            $this->whereSlug($qb,@$params["slug"]);
        }

        // recherche par job
        if(@$params["job"]){
            $this->whereJob($qb,@$params["job"]);
        }

        // recherche par city
        if(@$params["city"]){
            $this->whereCity($qb,@$params["city"]);
        }

        // recherche par country
        if(@$params["country"]){
            $this->whereCountry($qb,@$params["country"]);
        }

        // recherche par specialization
        if(@$params["specialization"]){
            $this->whereSpecialization($qb,@$params["specialization"]);
        }

        // ordre d'affichage par id
        if(@$params['order_id']){
            $order = strtoupper(trim($params['order_id'])) == "ASC" ? "ASC" : "DESC";
            $qb->orderBy("d.id",$order);
        }

        if(!@$params['order_id'] && !@$params["order_name"]){
            $params["order_name"] = "asc";
        }

        // ordre d'affichage par nom de programme
        if(@$params['order_name']){
            $order = strtoupper(trim($params['order_name'])) == "ASC" ? "ASC" : "DESC";
            $qb->orderBy("u.username",$order);
        }

        // ordre d'affichage par date e production
        if(@$params['order_year']){
            $order = strtoupper(trim($params['order_year'])) == "ASC" ? "ASC" : "DESC";
            $qb->orderBy("d.createAt",$order);
        }

        return $this;
    }

    public function search($params = array(),$limit = 20,$offset=0){
		$qb = $this->_em->createQueryBuilder();

        $this->myCurrentParams = $params;

		$qb->select("d")
		->from(Doctor::class,"d")
		->leftJoin("d.user","u")
        ->leftJoin("u.country","country")
        ->leftJoin("u.city","city")
        ->leftJoin("u.userType","userType")
        ->leftJoin("d.doctorType","doctorType")
        ->leftJoin("d.job","job")
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
            $qb->andWhere($qb->expr()->in("d.id",":id"));
        }
        else{
            $qb->andWhere($qb->expr()->eq("d.id",":id"));
        }
        $qb->setParameter("id",$value);
    }

	public function whereTerms(QueryBuilder $qb,$value){
        $terms_1 = $value;

        if(@$this->myCurrentParams['_search_mode'] == "google"){

  			$qb2 = $this->_em->createQueryBuilder();

        	$qb2->select("doc_spec_q.id")
			->from(DoctorSpecialization::class,"doc_spec_q")
			->innerJoin("doc_spec_q.user","u4")
			->innerJoin("doc_spec_q.specialization","spec")
			->where("u4.id = u.id")
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
                "u.username LIKE :q"
            )
            ->setParameter("q","%$value%");
        }
	}

	public function whereSlug(QueryBuilder $qb,$value){
        $qb->andWhere($qb->expr()->eq("u.slug",":user_slug"))
        ->setParameter("user_slug",$value);
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

  	public function whereJob(QueryBuilder $qb,$value){
  		
        if(is_numeric($value)){
            $qb->andWhere("job.id = :job");
        }
        else{
            $qb->andWhere("job.slug = :job");
        }
        $qb->setParameter("job",$value);
  	}

    public function whereDoctorType(QueryBuilder $qb,$value){
        
        if(is_numeric($value)){
            $qb->andWhere("doctorType.id = :doctor_type");
        }
        else{
            $qb2->andWhere("doctorType.slug = :doctor_type");
        }
        $qb->setParameter("doctor_type",$value);
    }


  	public function whereSpecialization(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

        $qb2->select("doc_spec.id")
		->from(DoctorSpecialization::class,"doc_spec")
		->innerJoin("doc_spec.user","u3")
		->innerJoin("doc_spec.specialization","specialization")
		->where("u3.id = u.id");
        
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
        $qb = $this->createQueryBuilder('d');
        
        $qb
        ->leftJoin("d.user","user")
        ->leftJoin("user.country","country")
        ->leftJoin("user.city","city")
        ->leftJoin("user.userType","userType")
        ->leftJoin("d.doctorType","doctorType")
        ->leftJoin("d.job","job")
        ->select('count(d.id)');

        $this->addWhereClause($qb, $params);

        return $qb->getQuery()
        ->getSingleScalarResult();
    }

}
