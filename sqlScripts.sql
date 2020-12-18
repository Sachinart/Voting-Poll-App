--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS login_attempts (
  user_id int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS members (
	id				INT(11)			NOT NULL AUTO_INCREMENT,
	firstName		VARCHAR(50)		NOT NULL,
	lastName		VARCHAR(50)		NOT NULL,
	email			VARCHAR(50)		NOT NULL,
	password		CHAR(128)		NOT NULL,
	salt			CHAR(128)		NOT NULL,
	isAllowed		INT(1)			NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS votes (
	id					INT(11) 		NOT NULL AUTO_INCREMENT,
	voteFrom			INT(11) 		NOT NULL,
	voteTo				INT(11) 		NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (voteFrom) REFERENCES members(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;