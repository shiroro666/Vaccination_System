CREATE EVENT match_appointment
    ON SCHEDULE EVERY 1 HOUR
    DO 
      with t1 as(
        select aid,adate,atime,providerId,test.patient.prionum,email,slot,test.patient.patientId,distance 
        from test.preference, test.appointment, test.patient, test.grouptype
        where weekday(adate)=test.preference.weekday and 
        test.patient.patientId=test.preference.patientId and 
        test.patient.prionum=test.grouptype.prionum and 
        test.grouptype.startdate <= adate and 
        adate > DATE_ADD(CURDATE(), INTERVAL 7 DAY) and 
        TIMESTAMPDIFF(hour, slot, atime) >= 0 and 
        TIMESTAMPDIFF(hour, slot, atime)<4),
      t2_1 as (
        select * from t1 
        where aid not in (select aid from test.offer where status='offer' or status='accepted' or status='complete') and
        patientId not in (select patientId from test.offer where status='offer' or status='accepted' or status='complete')
      ),
      t2_2 as (
        select t2_1.aid, t2_1.patientId from t2_1, test.offer where t2_1.aid=offer.aid and t2_1.patientId=offer.patientId and 
        (status='missed' or status='declined' or status='cancelled')
      ),
      t2 as (
        select t2_1.aid,t2_1.adate,t2_1.atime,t2_1.providerId,t2_1.prionum,t2_1.email,t2_1.slot,t2_1.patientId,t2_1.distance 
        from t2_1 left join t2_2 on t2_1.aid=t2_2.aid and t2_1.patientId=t2_2.patientId where 
        t2_2.aid is null or t2_2.patientId is null
      ),
      t3 as (
        select aid, adate, atime, t2.providerId, provider.name, provider.address, t2.patientId, t2.prionum, t2.email,distance, 
        111.111 * 0.62 
        * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(test.provider.latitude))
        * COS(RADIANS(test.patient.latitude))
        * COS(RADIANS(test.provider.longitude - test.patient.longitude))
        + SIN(RADIANS(test.provider.latitude))
        * SIN(RADIANS(test.patient.latitude))))) AS distance_in_miles
        from t2, test.provider, test.patient 
        where test.provider.providerId=t2.providerId and 
        test.patient.patientId=t2.patientId),
      t4 as (
        select patientId, prionum, email, providerId, name, address, aid, adate, atime, distance_in_miles from t3
        where distance_in_miles <= distance
      ),
      t5 as (
        select aid, count(*) as a_ct from t4 group by aid
      )
      select patientId, prionum, email, providerId, name, address, t4.aid, adate, atime, distance_in_miles
      from t4, t5 where t4.aid=t5.aid
      order by prionum, a_ct, adate, atime, distance_in_miles