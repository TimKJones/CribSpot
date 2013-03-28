-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 28, 2013 at 10:41 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cribspot`
--

-- --------------------------------------------------------

--
-- Table structure for table `sublets`
--

CREATE TABLE IF NOT EXISTS `sublets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL,
  `building_type_id` int(11) NOT NULL,
  `date_begin` date NOT NULL,
  `date_end` date NOT NULL,
  `number_bedrooms` int(11) NOT NULL,
  `price_per_bedroom` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_bathrooms` int(11) NOT NULL,
  `bathroom_type_id` int(11) DEFAULT NULL,
  `utility_type_id` int(11) NOT NULL,
  `utility_cost` int(11) NOT NULL,
  `deposit_amount` int(11) NOT NULL,
  `additional_fees_description` text COLLATE utf8_unicode_ci NOT NULL,
  `additional_fees_amount` int(11) NOT NULL,
  `marker_id` int(11) NOT NULL,
  `unit_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flexible_dates` tinyint(1) NOT NULL DEFAULT '1',
  `furnished_type_id` int(11) NOT NULL DEFAULT '1',
  `ac` tinyint(1) NOT NULL,
  `parking` tinyint(1) NOT NULL DEFAULT '0',
  `is_finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Dumping data for table `sublets`
--

INSERT INTO `sublets` (`id`, `user_id`, `university_id`, `building_type_id`, `date_begin`, `date_end`, `number_bedrooms`, `price_per_bedroom`, `payment_type_id`, `description`, `short_description`, `number_bathrooms`, `bathroom_type_id`, `utility_type_id`, `utility_cost`, `deposit_amount`, `additional_fees_description`, `additional_fees_amount`, `marker_id`, `unit_number`, `flexible_dates`, `furnished_type_id`, `ac`, `parking`, `is_finished`) VALUES
(5, 16, 4986, 1, '2013-05-05', '2013-08-25', 1, 500, 1, '', 'Two refrigerators, wireless internet, basketball court in back, 10+ parking spaces, couch in bedroom', 0, 2, 2, 20, 0, '', 0, 1, '-1', 1, 1, 1, 1, 0),
(6, 17, 4986, 2, '2013-05-01', '2013-08-25', 1, 400, 1, '', 'Rent my place, its nice and I have a cool roommate. ', 0, 1, 3, 0, 0, '', 0, 2, '7', 1, 1, 1, 1, 0),
(7, 18, 4986, 2, '2013-05-01', '2013-08-25', 1, 1050, 1, '', 'Great location on the Center of Campus. 4 rooms in the apartment. Wireless and 42 TV included. Washer/Dryer in apartment. Trash conveniently located in the hallway ', 0, 2, 2, 30, 1050, '', 0, 3, '-1', 2, 1, 1, 2, 0),
(8, 19, 4986, 1, '2013-05-08', '2013-08-15', 1, 512, 1, 'Need a subletter for May through August. Room is spacious (88 sq. feet-can very easily fit a full-size bed with a great amount of room to spare), has a lot of closet space, and comes with 2 dressers, a desk and chair, 2 lamps, a large mirror, and a 20-inch industrial size fan. \r\r\r\rThe house has 2 full bathrooms, a very large living room, a kitchen with two fridges, and a pretty sizable dining area. Living room also has a pretty cool spiral staircase. The house also has a fairly large porch. \r\r\r\r502 also has good wifi and a washer/dryer. \r\r\r\rThe house is located just a block away from CC Little Bus Stop, the Central Campus Recreation Building, and Strickland''s Market. 30 second walk from South University street. \r\r\r\rSubletters who sign on early may also be able to secure a parking spot or two. Sign up quick! \r\r\r\rRent listed is $512/month not inclusive of utilities, but it is NEGOTIABLE!', 'Spacious room with desk&chair, 2 lamps, 2 dressers, a large fan, good closet space. House has 2 bathrooms, large living and dining rooms, 2 fridges, and a large porch. ', 0, 2, 2, 50, 0, '', 0, 4, '-1', 1, 2, 1, 0, 0),
(9, 20, 4986, 2, '2013-06-01', '2013-08-25', 2, 300, 1, '', 'Great porch. Cable, heat, wireless internet, and water included.', 0, 2, 1, 0, 0, '', 0, 5, '9', 1, 2, 2, 2, 0),
(10, 21, 4986, 2, '2013-05-10', '2013-08-21', 4, 250, 1, 'Great location & price, new kitchen space', 'Great location', 0, NULL, 0, 0, 0, '', 0, 6, '7', 1, 1, 0, 1, 0),
(11, 22, 4986, 1, '2013-05-05', '2013-08-20', 1, 300, 1, 'Large Bedroom for summer sublet May 5-Aug 20. 12x12 size room. Sublet is for single room in a larger 3 story house. Room has its own private porch/entrance, which will be awesome in the summer.  Room is furnished but I can remove all items upon request. Other rooms in house are also being sublet-ed. If you are part of a group and want to live together with friends this house could be perfect for you. 3min walk to the union. 2 blocks from Main street. Parking spot avaliable', '12X12 room. Room has its own entrance and own private porch/deck Brand new kitchen. Fully furnished. 10 rooms in house', 0, 2, 3, 0, 350, '', 0, 7, '1', 2, 1, 2, 1, 0),
(12, 23, 4986, 2, '2013-05-06', '2013-08-12', 3, 300, 1, '', 'new kitchen, wall a/c, free internet, 5 min walk to campus, private entrance, dishwasher, fully furnished', 0, 2, 3, 0, 300, '', 0, 8, '8', 1, 1, 1, 1, 0),
(13, 24, 4986, 2, '2013-05-05', '2013-08-17', 1, 440, 1, 'Subletting 829 Tappan, right behind Ross. Spacious apartment, 2 large bedrooms, 1.5 baths, study area, large living room, 2 balconies, kitchen, fully furnished. Very convenient location for most spots on campus and a great value at $440/month. ', 'Large living room, spacious kitchen, 2 large bedrooms, 1.5 baths, study area, and an amazing location ', 0, 2, 2, 0, 0, '', 0, 9, '206', 1, 1, 1, 1, 0),
(14, 25, 4986, 1, '2013-05-03', '2013-08-25', 1, 300, 1, '', 'Nice place', 0, NULL, 0, 0, 0, '', 0, 1, '-1', 2, 0, 0, 0, 0),
(15, 26, 4986, 1, '2013-05-05', '2013-08-31', 1, 635, 1, '', 'large basement', 0, 2, 2, 0, 200, '', 0, 4, '-1', 1, 1, 1, 1, 0),
(16, 27, 4986, 1, '2013-05-05', '2013-08-15', 6, 500, 1, '', 'Awesome summer house. 5 minutes from South U bars and diag, 5 minutes from state and packard! Rent negotiable.', 0, 2, 1, 0, 75, '', 0, 12, '-1', 1, 1, 1, 1, 0),
(17, 28, 4986, 1, '2013-05-05', '2013-08-20', 1, 300, 1, 'This is the perfect room to sublet with a friend! It is large and spacious double that is located on Greenwood street! This is an ideal location for spring/summer term because all the neighbors are very friendly and something is always going on! The house has a brand new kitchen and basement! This room is a double and one of the cheapest rents in Ann Arbor which is perfect to share with a friend and save money! Girl sub-letters are preferred ', 'Brand new kitchen and basement, large attic room that can be shared as a double, inexpensive and great location for classes and going out', 0, 2, 3, 0, 0, '', 0, 13, '-1', 1, 1, 2, 1, 0),
(18, 29, 4986, 2, '2013-05-10', '2013-08-01', 1, 700, 1, 'Bedroom in the new Landmark apartments, price and dates negotiable. 5 bedroom unit.', 'Brand new building', 0, 2, 2, 20, 0, '', 0, 14, '313', 1, 1, 1, 2, 0),
(19, 30, 4986, 2, '2013-06-15', '2013-08-01', 1, 520, 1, '', 'Only 1 year old, kept very neat', 0, 2, 2, 16, 200, '', 0, 14, '313', 2, 1, 1, 1, 0),
(20, 31, 4986, 1, '2013-05-07', '2013-08-15', 4, 325, 1, '', '8 Bedroom house with multiple subletters. Great location on Vaughn Street, very close to the Diag and South University. Furnished house with great amenities (e.g. basketball court).', 0, 2, 2, 30, 0, '', 0, 1, '7', 1, 1, 1, 1, 0),
(21, 32, 4986, 2, '2013-05-04', '2013-08-25', 1, 400, 1, '', 'Nice room connected to the communal bathroom, access to kitchen/living room', 0, 2, 1, 0, 0, '', 0, 17, '1', 1, 2, 1, 2, 0),
(22, 33, 4986, 3, '2013-05-07', '2013-08-15', 1, 250, 1, 'Can be furnished or unfurnished, whichever you''d like. Very nice place, recently remodeled. Comfortable living room, full kitchen, 4 BR/2 BA, personal study and nice bedroom with large closet. Bathroom will be private or shared with one other, depending on if roommate finds subletter.', 'Very nice place, great location, one block from South U, personal study room + bedroom', 0, 1, 1, 0, 0, '', 0, 18, '-1', 1, 1, 1, 1, 0),
(23, 34, 4986, 2, '2013-05-10', '2013-08-30', 3, 250, 1, '', '3 beds - laundry machine in the house', 0, 1, 3, 0, 0, '', 0, 19, '-1', 1, 1, 1, 1, 0),
(24, 35, 4986, 1, '2013-05-01', '2013-08-28', 6, 450, 1, '', 'The whole house has been recently remodeled.Sink/ vanity in each room, queen size bed and dresser included. large closets', 0, 2, 2, 0, 0, '', 0, 20, '-1', 1, 1, 1, 1, 0),
(25, 36, 4986, 1, '2013-05-07', '2013-08-21', 5, 400, 1, '', 'great location, laundry and parking, large bedrooms, top floor loft, utilities paid by landlord, AIR CONDITIONING, nice kitchen, GREAT PLACE', 0, 1, 1, 0, 0, '', 0, 21, '-1', 1, 2, 1, 1, 0),
(26, 37, 4986, 1, '2013-05-05', '2013-08-25', 1, 600, 1, '', 'House with the yellow M at State and Hoover.  Back room of conjoined room (have to walk through someone''s room to get to yours).  Free washer and drier in basement.  Internet, cable, water, power included.', 0, 2, 1, 0, 0, '', 0, 22, '-1', 1, 1, 2, 1, 0),
(27, 38, 4986, 1, '2013-05-02', '2013-06-30', 1, 600, 1, '', '5 house mates, rooftop patio, fully furnished, good AC, free parking', 0, 2, 3, 0, 0, '', 0, 23, '-1', 1, 1, 1, 1, 0),
(28, 39, 4986, 2, '2013-05-01', '2013-08-24', 2, 450, 1, '2 BR, Best Location, UM Campus, Spring-Summer Term (May-Aug)\r\r\r\r608 Monroe, Central Campus\r\r\r\rGreat 2 Bedroom Fully Furnished Apartment with Parking Space on 608 Monroe (The BEST Central Campus Location)\r\r\r\r-Across the street from UM Law School, 2 blocks from Ross Business School, 4 blocks from the Michigan Diag, right behind South Quad, and 2 blocks from Michigan Union\r\r-Comes Fully Furnished (1 twin bed, 1 full bed, desks, dressers, 2 sofas, kitchen table, fridge, microwave)\r\r-Heating and Water included in price\r\r-Laundry in the Basement\r\r-FREE Parking Space right behind building\r\r\r\r\r\rAvailable from May 1st-Aug 24', '-Across the street from UM Law School, 2 blocks from Ross Business School, 4 blocks from the Michigan Diag, right behind South Quad, and 2 blocks from Michigan Union -Comes Fully Furnished (1 twin bed, 1 full bed, desks, dressers, 2 sofas, kitchen table, ', 0, 1, 2, 20, 950, '', 0, 24, '-1', 2, 1, 1, 1, 0),
(29, 40, 4986, 2, '2013-05-05', '2013-08-26', 2, 400, 1, '', '2 bedrooms, 4 beds, 1.5 bathrooms, fully furnished, bedrooms upstairs, large living room and kitchen downstairs, wireless internet, all utilities included (except electricity), prices negotiable', 0, NULL, 2, 40, 0, '', 0, 25, '13', 1, 1, 1, 2, 0),
(30, 41, 4986, 2, '2013-05-10', '2013-08-10', 2, 500, 1, 'Massive bedrooms and closets. Shower and tub. Leather couches included. Water included. ', 'Huge room and apartment!', 0, 2, 3, 0, 0, '', 0, 26, '3', 1, 2, 1, 1, 0),
(31, 42, 4986, 3, '2013-05-06', '2013-08-16', 2, 400, 1, '', 'Two available rooms in a two-story apartment near main st. 8 minute walk from Michigan Union. Free parking spot included!', 0, 1, 2, 13, 0, '', 0, 27, '-1', 1, 2, 2, 1, 0),
(32, 43, 4986, 2, '2013-05-10', '2013-08-20', 1, 400, 1, 'The Dean Apt. F is a 2 bathroom, 3 bedroom two-floor apartment that can accommodate up to 3 beds. It comes fully furnished with a newly remodeled kitchen and new living room furniture. All rooms are very spacious with storage areas above the equally spacious bedroom closets. The rental price is negotiable as well as renting the entire unit or renting by room. The price of the entire apartment is $2100/mth and for this specific room is $725/month. Can be rented for whole or partial time frame. \r\rThis specific room has a private balcony and private AC Unit, in addition to a new Full bed and mattress, a very spacious closet, personal desk, and a large dresser. It is always very bright, and incredibly spacious. \r\r\r\r\r\rOther apartment features include: \r\r\r\rOn-site laundry\r\rDishwasher, fridge, microwave\r\rDirecTV\r\rPrivate entrance\r\rParking pass availability\r\rLess than 10 min walk from U of M Diag\r\rLess than 5 min walk from IM building/ Burns Park/Ross School of Business\r\rWalk or short drive to Kroger/ CVS\r\rSergeant Peppers Liquor/Convenience store is less than 1 minute away\r\rVery close to numerous AATA and UM bus routes\r\rA/C unit\r\rNo pets\r\rVanity bath area\r\rGarbage and recycling services\r\rInternet, water, heat included\r\rFast maintenance response', 'Two floor apartment, 2 bathrooms, Free WI-FI/Heat,Water,Gas, Personal AC unit & Balcony in bedoom, Full Bed, Huge Closet and Dresser, Bright room. ', 0, 2, 1, 0, 0, '', 0, 8, 'F', 1, 1, 1, 2, 0),
(33, 44, 4986, 1, '2013-05-05', '2013-08-25', 1, 500, 1, '', 'Large Room can fit up to two beds in the room, less than a minute walk to the IM building,', 0, 2, 2, 50, 100, '', 0, 29, '-1', 1, 1, 1, 1, 0),
(34, 45, 4986, 2, '2013-06-01', '2013-08-20', 2, 300, 1, '', 'Wireless available, centrally located.', 0, 1, 3, 0, 0, '', 0, 30, '5', 1, 1, 1, 1, 0),
(35, 46, 4986, 2, '2013-05-01', '2013-08-21', 3, 600, 1, '', 'All utilities included, including cable and wifi, air-conditioning units in each bedroom, coin laundry available on-site, one parking space included', 0, NULL, 1, 0, 0, '', 0, 31, '31', 1, 1, 1, 1, 0),
(36, 47, 4986, 1, '2013-05-05', '2013-08-25', 1, 350, 1, '', 'Brand new porch, big room and large downstairs.', 0, 2, 0, 0, 0, '', 0, 32, '-1', 1, 0, 2, 1, 0),
(37, 48, 4986, 2, '2013-05-01', '2013-08-27', 3, 300, 1, '', 'Wireless internet, access to laundry, very negotiable price, comes with two parking spot, across the street from Sgt Peppers', 0, 2, 1, 0, 0, '', 0, 6, '7', 1, 1, 1, 1, 0),
(38, 49, 4986, 1, '2013-05-05', '2013-08-31', 1, 500, 1, 'The room is not overly large, but has plenty of room for the necessities. Spacious closet with much shelving. Has a large walkout porch from the bedroom/stairs going down outside. There is a halfcourt basketball hoop and more than the necessary parking accommodations. There are 2 refrigerators in the kitchen, and multiple living rooms. ', 'Large house, 8 bedroom, much parking, 1/2court bball, 2 fridges, BYO internet/cable', 0, 2, 3, 0, 0, '', 0, 34, '5', 1, 3, 1, 1, 0),
(39, 50, 4986, 1, '2013-05-06', '2013-08-28', 1, 450, 1, '', 'Huge room with walk in closet, large kitchen with two refrigerators, free laundry in basement', 0, 2, 0, 0, 0, '', 0, 35, '-1', 1, 1, 0, 2, 0),
(40, 51, 4986, 1, '2013-05-10', '2013-08-15', 5, 350, 1, 'Unit 7 in an 8 unit house located on Vaughn St., minutes from South U. Very good sized room on the second floor. Has a back entrance on the fire escape that is a stairway. Very nice for coming back late at night or walking to your car in the parking lot. Near a bathroom with two showers and one toilet. Comes with a bed, a desk, a mini fridge, and a media center.\r\r\r\rReceived an 89/100 on Walkscore.com. 200 feet from AATA bus stop if you need the bus.\r\rhttp://www.walkscore.com/score/1001-vaughn-st-ann-arbor-mi-48104', 'Huge basement, washer/dryer, A/C, 3 bathrooms, wrap around porch, basketball court in backyard', 0, 2, 3, 0, 0, '', 0, 1, '7', 1, 1, 1, 1, 0),
(41, 52, 4497, 1, '2013-05-05', '2013-08-25', 2, 580, 1, '', 'Large basement and 5 bedroom unit upstairs', 0, 2, 3, 0, 0, '', 0, 37, 'B', 1, 3, 1, 1, 0),
(42, 53, 4497, 1, '2013-05-06', '2013-08-25', 4, 350, 1, '', 'House has 4 spacious rooms (two with roof access). Additionally the house has a large living room, kitchen with dishwasher, fridge, and stove, and a finished basement with a bar. Overall great place with great location close to the bars.', 0, 2, 3, 0, 0, '', 0, 38, '-1', 1, 2, 1, 1, 0),
(43, 54, 4497, 1, '2013-05-05', '2013-08-20', 3, 315, 1, '', 'Super cheap rent, close to campus, 1 block from the D bus, nice backyard, huge garage', 0, 2, 2, 40, 0, '', 0, 39, '-1', 1, 3, 1, 1, 0),
(44, 55, 4986, 1, '2013-05-05', '2013-08-25', 1, 400, 1, '', 'Large Individual Bedroom. Full size Bed. House is great. All amendities incdlued. Basketball hoop in backyard.', 0, 2, 2, 0, 0, '', 0, 1, '-1', 1, 1, 2, 1, 0),
(45, 56, 4541, 1, '2013-06-01', '2013-05-30', 5, 400, 1, '', 'newly built.  5 large bedrooms, big basement, two car garage', 0, 1, 3, -1, 800, '', 0, 41, '-1', 2, 2, 1, 1, 0),
(46, 57, 4986, 1, '2013-06-20', '2013-08-20', 1, 350, 1, '', 'Bedroom with private bathroom connected', 0, 1, 3, -1, 100, '', 0, 1, '-1', 2, 1, 1, 1, 0),
(47, 58, 4986, 1, '2013-05-15', '2013-08-15', 1, 400, 1, 'One basement room in a 6 bedroom house at East U and Packard. Technically we have 3 other rooms available too... so if you have friends who want to sublet do it! There''s a kitchen, living room, and family room. Laundry is free and right next to your room! What an easy trip! There will be three people living here and they are pretttty cool. Right by Sgt. Pepper''s. There''s even a spiral staircase! Whoaaaa. Room has a full bed, a desk, a closet, and two dressers. Comcast is set up.', 'Big basement room, free laundry, two fridges, cool people.', 0, 1, 3, 0, 0, '', 0, 42, '-1', 1, 1, 1, 1, 0),
(48, 59, 4986, 2, '2013-05-05', '2013-08-25', 1, 350, 1, 'This is a very large bedroom in a 2 bedroom unit on the third floor of an apartment building. The building is in a prime location on campus, 5 minutes from the b school, the gym, a bus stop, and the union. The unit has an air conditioning unit. \r\rOther roommate is a female in the dental school and is in class almost every day all day. Super easy to live with. We''re looking for only females to reply to this.', 'Large bedroom, great balcony, close to everything, air conditioning', 0, 1, 3, 0, 0, 'Internet and cable', 0, 26, '14', 1, 1, 1, 2, 0),
(49, 60, 4986, 1, '2013-04-30', '2013-08-20', 1, 455, 1, 'Private bedroom available in well maintained house. Located across the street from the nursing school and near the medical campus, central campus and State St. areas. Parking and laundry onsite. Kitchen and living areas are fully furnished.  ', 'free onsite parking and laundry, fully furnished common areas', 0, 1, 3, 0, 0, '', 0, 43, '-1', 1, 2, 0, 1, 0),
(50, 61, 4986, 2, '2013-05-05', '2013-08-16', 1, 529, 1, '', 'Room available in Landmark apartments on the second floor in a four room apartment. Rent is 1/2 of normal rent, lease is month to month, if you are a student staying in ann arbor for spring/summer term, this is great location, amazing amenities, get a gro', 0, 2, 3, 0, 0, '', 0, 14, '-1', 1, 1, 1, 1, 0),
(51, 62, 4986, 1, '2013-05-06', '2013-08-28', 1, 450, 1, '5 bedroom house with large basement with laundry and room for storage, 2 fridges, 2 full baths, big kitchen with dishwasher, electric stove top, microwave, lots of storage space and oven, dining room and living room. Fully furnished 5 room house with locks on doors and all rooms being sublet too for the summer. Wifi. So close to campus/good eateries. Helpful/responsive landlords, and it''s in great condition.\rThe utility bill is for electric and it will be much cheaper in the summer since there is no heat being used. ', '5 bedroom house with large basement with laundry and room for storage, 2 fridges, 2 full baths, big kitchen with dishwasher, electric stove top, microwave, lots of storage space and oven, dining room and living room. Fully furnished 5 room house with lock', 0, 2, 3, 0, 0, '', 0, 35, '-1', 1, 1, 0, 0, 0),
(52, 63, 4986, 2, '2013-05-05', '2013-08-31', 2, 625, 1, '', '2 bedroom, 2 floor apartment on Oakland. Newly renovated kitchen & bath. 2 parking spots, A/C, all utilities included except electricity', 0, 2, 1, 0, 0, '', 0, 44, '4', 1, 1, 1, 1, 0),
(53, 64, 4986, 2, '2013-05-01', '2013-07-28', 1, 700, 1, 'I''m looking to sublet my room in zaragon from May- July 28th. This is a newer apartment complex, and everything is modern! This is a four bedroom, two bathroom unit. The building is locked 24/7 with keycard access and a buzz in system for guests. Workout room on first floor, with stores, restaurants and bars near by! Minute from Diag, B-school, school of ed, and just minutes from everything else on central campus!!! Rent is negotiable!', 'Internet/cable included, keycard entry, workout room, close to EVERYTHING, great neighbors and management ', 0, 2, 3, 0, 0, '', 0, 3, '-1', 1, 1, 1, 1, 0),
(54, 65, 4986, 1, '2013-05-01', '2013-08-31', 6, 500, 1, '', 'front porch, laundry, parking, air conditioning, dishwasher', 0, NULL, 0, 0, 0, '', 0, 21, '-1', 1, 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
