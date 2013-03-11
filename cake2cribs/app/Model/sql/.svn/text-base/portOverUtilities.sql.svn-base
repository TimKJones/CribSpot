insert into cake2cribs.listings(marker_id, lease_range, unit_type, unit_description, beds, baths, rent, electric, water, heat, air, parking, furnished, url, realtor_id) 
	select m.marker_id, l.lease_range, l.unit_type, l.unit_description, l.beds, l.bathrooms, l.rent, l.electric, l.water, l.heat, l.air, l.parking, l.furnished, l.url, r.realtor_id 
	from a2cribs_old.Houses l, cake2cribs.realtors r, cake2cribs.markers m
	where l.company = r.company and m.latitude = l.latitude and m.longitude = l.longitude and m.alternate_name = l.alternate_name and m.unit_type = l.unit_type and m.address = l.address;

insert into cake2cribs.markers(alternate_name, unit_type, address, latitude, longitude)
	select distinct alternate_name, unit_type, address, latitude, longitude from a2cribs_old.Houses;

update cake2cribs.listings l, a2cribs_old.Houses h, cake2cribs.markers m set l.marker_id = m.marker_id 
where m.alternate_name=h.alternate_name and m.unit_type=h.unit_type and m.address=h.address and m.latitude=h.latitude and m.longitude = h.longitude
and l.lease_range = h.lease_range and l.unit_type = h.unit_type and l.unit_description = h.unit_description and l.beds = h.beds and l.baths = h.bathrooms and l.rent = h.rent and l.electric = h.electric and l.water = h.water and l.heat = h.heat and l.air = h.air and l.parking = h.parking and l.furnished = h.furnished and l.url = h.url;


select distinct m.marker_idfrom a2cribs_old.Houses h, cake2cribs.markers m
where m.alternate_name=h.alternate_name and m.unit_type=h.unit_type and m.address=h.address and m.latitude=h.latitude and m.longitude = h.longitude

create table listings_temp like listings; insert listings_temp select * from listings;

alter table listings 
change column electric electric varchar(8);
alter table listings 
change column air air varchar(8);
alter table listings 
change column heat heat varchar(8);
alter table listings 
change column water water varchar(8);
alter table listings 
change column parking parking varchar(8);
alter table listings 
change column furnished furnished varchar(8);

alter table listings 
change column electric electric boolean;
alter table listings 
change column air air boolean;
alter table listings 
change column heat heat boolean;
alter table listings 
change column water water boolean;
alter table listings 
change column parking parking boolean;
alter table listings 
change column furnished furnished boolean;


update cake2cribs.listings set water = true where listing_id in (select listing_id from cake2cribs.listings_temp where water = 'Y');
update cake2cribs.listings set water = false where listing_id in (select listing_id from cake2cribs.listings_temp where water = 'N');
update cake2cribs.listings set water = null where listing_id in (select listing_id from cake2cribs.listings_temp where water = '?');
update cake2cribs.listings set heat = true where listing_id in (select listing_id from cake2cribs.listings_temp where heat = 'Y');
update cake2cribs.listings set heat = false where listing_id in (select listing_id from cake2cribs.listings_temp where heat = 'N');
update cake2cribs.listings set heat = null where listing_id in (select listing_id from cake2cribs.listings_temp where heat = '?');
update cake2cribs.listings set air = true where listing_id in (select listing_id from cake2cribs.listings_temp where air = 'Y');
update cake2cribs.listings set air = false where listing_id in (select listing_id from cake2cribs.listings_temp where air = 'N');
update cake2cribs.listings set air = null where listing_id in (select listing_id from cake2cribs.listings_temp where air = '?');
update cake2cribs.listings set parking = true where listing_id in (select listing_id from cake2cribs.listings_temp where parking = 'Y');
update cake2cribs.listings set parking = false where listing_id in (select listing_id from cake2cribs.listings_temp where parking = 'N');
update cake2cribs.listings set parking = null where listing_id in (select listing_id from cake2cribs.listings_temp where parking = '?');
update cake2cribs.listings set furnished = true where listing_id in (select listing_id from cake2cribs.listings_temp where furnished = 'Y');
update cake2cribs.listings set furnished = false where listing_id in (select listing_id from cake2cribs.listings_temp where furnished = 'N');
update cake2cribs.listings set furnished = null where listing_id in (select listing_id from cake2cribs.listings_temp where furnished = '?');
update cake2cribs.listings set electric = true where listing_id in (select listing_id from cake2cribs.listings_temp where electric = 'Y');
update cake2cribs.listings set electric = false where listing_id in (select listing_id from cake2cribs.listings_temp where electric = 'N');
update cake2cribs.listings set electric = null where listing_id in (select listing_id from cake2cribs.listings_temp where electric = '?');


update cake2cribs.listings set water = false where water = 'N';
update cake2cribs.listings set water = null where water = '?';
update cake2cribs.listings set heat = true where heat = 'Y';
update cake2cribs.listings set heat = false where heat = 'N';
update cake2cribs.listings set heat = null where heat = '?';
update cake2cribs.listings set air = true where air = 'Y';
update cake2cribs.listings set air = false where air = 'N';
update cake2cribs.listings set air = null where air = '?';
update cake2cribs.listings set parking = true where parking = 'Y';
update cake2cribs.listings set parking = false where parking = 'N';
update cake2cribs.listings set parking = null where parking = '?';
update cake2cribs.listings set furnished = true where furnished = 'Y';
update cake2cribs.listings set furnished = false where furnished = 'N';
update cake2cribs.listings set furnished = null where furnished = '?';

update cake2cribs.listings set water = true where marker_id in (select markerid from a2cribs_old.Houses where water = 'Y');
update cake2cribs.listings set water = false where marker_id in (select markerid from a2cribs_old.Houses where water = 'N');
update cake2cribs.listings set water = null where marker_id in (select markerid from a2cribs_old.Houses where water = '?');
update cake2cribs.listings set heat = true where marker_id in (select markerid from a2cribs_old.Houses where heat = 'Y');
update cake2cribs.listings set heat = false where marker_id in (select markerid from a2cribs_old.Houses where heat = 'N');
update cake2cribs.listings set heat = null where marker_id in (select markerid from a2cribs_old.Houses where heat = '?');
update cake2cribs.listings set air = true where marker_id in (select markerid from a2cribs_old.Houses where air = 'Y');
update cake2cribs.listings set air = false where marker_id in (select markerid from a2cribs_old.Houses where air = 'N');
update cake2cribs.listings set air = null where marker_id in (select markerid from a2cribs_old.Houses where air = '?');
update cake2cribs.listings set parking = true where marker_id in (select markerid from a2cribs_old.Houses where parking = 'Y');
update cake2cribs.listings set parking = false where marker_id in (select markerid from a2cribs_old.Houses where parking = 'N');
update cake2cribs.listings set parking = null where marker_id in (select markerid from a2cribs_old.Houses where parking = '?');
update cake2cribs.listings set furnished = true where marker_id in (select markerid from a2cribs_old.Houses where furnished = 'Y');
update cake2cribs.listings set furnished = false where marker_id in (select markerid from a2cribs_old.Houses where furnished = 'N');
update cake2cribs.listings set furnished = null where marker_id in (select markerid from a2cribs_old.Houses where furnished = '?');

update cake2cribs.listings set lease_range = 'fall' where lease_range='Fall';
update cake2cribs.listings set lease_range = 'spring' where lease_range='Spring';
update cake2cribs.listings set unit_type='house' where unit_type='House';
update cake2cribs.listings set unit_type='apt' where unit_type='Apartment';
update cake2cribs.listings set unit_type='duplex' where unit_type='Duplex';
update cake2cribs.listings set unit_description=null where unit_description='NA';
update cake2cribs.listings set lease_range='fall' where lease_range='Fall';
update cake2cribs.listings set lease_range='spring' where lease_range='Spring';	
update cake2cribs.listings set lease_range='other' where lease_range='Other';
update cake2cribs.listings set url=null where url='?';



delete from markers_temp m1 where exists (
select * from markers_temp m2 where m2.marker_id<>m1.marker_id and m2.alternate_name=m1.alternate_name and m2.unit_type=m1.unit_type and m2.address=m1.address and m2.latitude=m1.latitude and m2.longitude = m1.longitude)
and m1.marker_id <> (select min(m2.marker_id) from markers_temp m2 where m2.alternate_name=m1.alternate_name and m2.unit_type=m1.unit_type and m2.address=m1.address and m2.latitude=m1.latitude and m2.longitude = m1.longitude);

DELETE m1 FROM markers_temp m1, markers_temp m2 WHERE m1.marker_id > m2.marker_id AND m2.alternate_name=m1.alternate_name and m2.unit_type=m1.unit_type and m2.address=m1.address and m2.latitude=m1.latitude and m2.longitude = m1.longitude;

DELETE m1 FROM markers_temp m1, markers_temp m2 WHERE m1.marker_id > m2.marker_id AND m2.alternate_name=m1.alternate_name and m2.unit_type=m1.unit_type and m2.address=m1.address and m2.latitude=m1.latitude and m2.longitude = m1.longitude;

insert into markers (alternate_name, unit_type, address, latitude, longitude) select 