## Talend Runner PHP
#### Description
Run Talend jobs in PHP

#### Usage 

	use Softmetrix\TalendRunner\TalendRunner;
	
    $jobPath = '/path-to-jobs/TestJob_0.1/TestJob/TestJob_run';
    $talendRunner = new TalendRunner($jobPath);
    $talendRunner->addContextParam('dbhost', '127.0.0.1');
    $talendRunner->addContextParam('dbport', '3306');
    $talendRunner->addContextParam('dbuser', 'usr12');
    $talendRunner->addContextParam('dbpass', 'p9432Y$#');
    $talendRunner->addContextParam('dbname', 'testdb');
    $talendRunner->addContextParam('dbtable', 'talendtest');
    $talendRunner->addContextParam('filepath', '/path-to-csv-file.csv');
    $output = $talendRunner->run();
    	  
Variable $jobPath should contain path to job script without extension (.bat, .sh, ...). Library will automatically append extension depending on host operating system. 