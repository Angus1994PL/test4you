<?php

/**
 * Description of statistics
 * @author marcinw
 */
class Statistics
{

    /**
     * Get Statistic object by given id
     * @param type $id
     * @return \Statistic
     */
    public static function getStatisticById($id)
    {
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams('select * from statistics WHERE id=?;', array((int) $id));
        if ($result) {
            $obj = DataBase::GetDbInstance()->FetchObject($result);
            if ($obj != false) {
                return New Statistic($obj);
            }
        }
    }

    /**
     * Add given statistic object to database.
     * @param \Statistic $stat
     */
    public static function addStatistic(\Statistic $stat)
    {
        $query = 'insert into statistics (object, rent, location, quartersOrStreet, date, priceFrom, priceTo, areaFrom, areaTo, quantity) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
        $params = array($stat->getObject(), $stat->getRent(), $stat->getLocation(), $stat->getQuartersOrStreet(), date('Y-m-d'), $stat->getPriceFrom(), $stat->getPriceTo(), $stat->getAreaFrom(), $stat->getAreaTo(), 1);
        DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
    }

    /**
     * Save given Statistic object to database.
     * @param Statistic $stat
     */
    public static function editStatistic(\Statistic $stat)
    {
        $query = 'update statistics SET object=?, rent=?, location=?, quartersOrStreet=?, date=?, priceFrom=?, priceTo=?, areaFrom=?, areaTo=?, quantity=? WHERE id=?;';
        $params = array($stat->getObject(), $stat->getRent(), $stat->getLocation(), $stat->getQuartersOrStreet(), $stat->getDate(), $stat->getPriceFrom(),
            $stat->getPriceTo(), $stat->getAreaFrom(), $stat->getAreaTo(), $stat->getQuantity(), $stat->getId());
        DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
    }

    /**
     * Add or edit if exists, given Statistic object.
     * @param Statistic $stat
     */
    public static function addEditStatistic(\Statistic $stat)
    {
        $s = self::getStatisticById($stat->getId());
        if ($s === null) {
            self::addStatistic($stat);
        } else {
            self::editStatistic($stat);
        }
    }
    
    /**
     * Deletes old statistic entries
     */
    public static function deleteOldStatistics()
    {
        $days = 356;
        if (isset(Config::$StatisticValidityInterval)){
            $days = Config::$StatisticValidityInterval;
        }
        
        $query = 'delete from statistics where date < DATE_SUB(NOW(), INTERVAL ? DAY)';
        DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array($days));
    }

    /**
     * Get list of Statistic
     * @param type $filtry
     * @return array of \Statistic
     */
    public static function getStatistics($filtry)
    {
        $query = 'select s.* from statistics s where 1=1 ';
        if (is_array($filtry) && sizeof($filtry) > 0) {
            foreach ($filtry as $key => $value) {
                switch ($key) {
                    case 'quarter':
                    case 'quarters_or_street':
                        $query .= ' and s.quartersOrStreet=?';
                        break;
                    case 'dateFrom':
                        $query .= ' and s.date >= ?';
                        break;
                    case 'dateTo':
                        $query .= ' and s.date <= ?';
                        break;
                    default:
                        if ($value === null) {
                            $query .= " and s.$key is null";
                            unset($filtry[$key]);
                        } else {
                            $query .= " and s.$key=?";
                        }
                        break;
                }
            }
        }

        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $filtry);
        $list = array();
        if ($result) {
            while ($obj = DataBase::GetDbInstance()->FetchObject($result)) {
                $list[] = new Statistic($obj);
            }
        }
        return $list;
    }
    
    /**
     * Get list of Statistic grouped by quantity 
     * @param type $filtry
     * @return array of \Statistic
     */
    public static function getStatisticsGrouped($filtry)
    {
        $query = 'select s.id, s.object, s.rent, s.location, s.quartersOrStreet, s.priceFrom, s.priceTo, s.areaFrom, s.areaTo, sum(s.quantity) as quantity from statistics s where 1=1 ';
        if (is_array($filtry) && sizeof($filtry) > 0) {
            foreach ($filtry as $key => $value) {
                switch ($key) {
                    case 'quarter':
                    case 'quarters_or_street':
                        $query .= ' and s.quartersOrStreet=?';
                        break;
                    case 'dateFrom':
                        $query .= ' and s.date >= ?';
                        break;
                    case 'dateTo':
                        $query .= ' and s.date <= ?';
                        break;
                    default:
                        if ($value === null) {
                            $query .= " and s.$key is null";
                            unset($filtry[$key]);
                        } else {
                            $query .= " and s.$key=?";
                        }
                        break;
                }
            }
        }
        
        $query .= ' group by s.object, s.rent, s.location, s.quartersOrStreet, s.priceFrom, s.priceTo, s.areaFrom, s.areaTo order by quantity desc';

        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $filtry);
        $list = array();
        if ($result) {
            while ($obj = DataBase::GetDbInstance()->FetchObject($result)) {
                $list[] = new Statistic($obj);
            }
        }
        return $list;
    }

    /**
     * Check if given array has required params
     * @param type $paramsArr
     * @return boolean
     */
    protected static function checkFilterParams($paramsArr)
    {
        if (sizeof($paramsArr) <= 0)
            return false;

        $pass = true;
        $requireParams = array('location', 'rent', 'object');
        foreach ($requireParams as $value) {
            if (!array_key_exists($value, $paramsArr) || trim($paramsArr[$value]) == '') {
             
                    $pass = false;
                    break;
            }
        }
        return $pass;
    }

    /**
     * Prepare given array to cast to Statistic object
     * @param array $filters
     * @return array
     */
    protected static function prepareFilters(array $filters)
    {
        $correctFilters = array('object', 'rent', 'location', 'quarters_or_street', 'date', 'priceFrom', 'priceTo', 'areaFrom', 'areaTo');

        foreach ($filters as $key => $value) {
            //zamiana filtra quarter na odpowiednik quarters_or_street.
            if ($key === 'quarter') {
                $filters['quarters_or_street'] = $filters[$key];
            }
            if (!in_array($key, $correctFilters)) {
                unset($filters[$key]);
            }
        }

        $filters['date'] = date('Y-m-d');
        if (!isset($filters['priceFrom']))
            $filters['priceFrom'] = null;

        if (!isset($filters['priceTo']))
            $filters['priceTo'] = null;

        if (!isset($filters['areaFrom']))
            $filters['areaFrom'] = null;

        if (!isset($filters['areaTo']))
            $filters['areaTo'] = null;

        return $filters;
    }

    /**
     * Register Statistic in database
     * @param RefreshEventArgs $args
     * @return type
     */
    public static function registerStatistic(\RefreshEventArgs $args)
    {
        //sprawdzenie czy sa wymagane filtry i czy nie jest to kolejna strona wyszukiwania
        if (!self::checkFilterParams($args->Filters) || $args->ActualPage > 1)
            return null;

        $filters = self::prepareFilters($args->Filters);
        $stat = self::getStatForFilters($filters);
        
        self::addEditStatistic($stat);
    }
    
    /**
     * Register Statistic in database
     * @param Offer $ofe
     * @return type
     */
    public static function registerStatisticForOffer(\Offer $ofe)
    {
        $filters['location'] = $ofe->GetLocation();
        $filters['rent'] = $ofe->GetRent();
        $filters['object'] = $ofe->GetObject();
        $filters['priceTo'] = $ofe->GetPrice();
        $filters['areaTo'] = $ofe->GetArea();
        
        if($ofe->GetQuarter() !== ''){
            $filters['quarters_or_street']=$ofe->GetQuarter();
        }
        
        $filters = self::prepareFilters($filters);
        $stat = self::getStatForFilters($filters);
        self::addEditStatistic($stat);
    }
    
    
    /**
     * Prepares a Statistic object for given filters. Gets it from database or creates a new one.
     * @param array $filters
     * @return Statistic
     */
    protected static function getStatForFilters($filters)
    {
        //sprawdzenie czy istnieje juz taki wpis. W razie potrzeby trzeba posplitowac wartosc filtra quarters_or_street 
        //i rozpatrzyc kazdy przypadek osobno (jesli bedzie wiecej niz jedna dzielnica/ulica)
        $stats = self::getStatistics($filters);
        if (sizeof($stats) > 0) {
            $stat = reset($stats);
            $stat->setQuantity($stat->getQuantity() + 1);
        } else {
            $stat = new Statistic(new stdClass());
            if (isset($filters['quarters_or_street'])) {
                $stat->setQuartersOrStreet($filters['quarters_or_street']);
            }
            $stat->setDate($filters['date']);
            $stat->setLocation($filters['location']);
            $stat->setRent($filters['rent']);
            $stat->setObject($filters['object']);
            $stat->setPriceFrom($filters['priceFrom']);
            $stat->setPriceTo($filters['priceTo']);
            $stat->setAreaFrom($filters['areaFrom']);
            $stat->setAreaTo($filters['areaTo']);
        }
        return $stat;
    }
    

}
