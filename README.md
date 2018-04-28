# Turbostar

The Turbostar system, named after the [family of DMUs of the same name](https://en.wikipedia.org/wiki/Turbostar), is a system within my larger [Open Rail](https://conorhaining.com/open-rail) project which is being undertaken as part of my BSc Computing Science degree at the university of Dundee.

## System Overview
The purpose of Turbostar is to collect, organise and orchastrate the data feed from Network Rail. Turbostar collects data from six of the seven data feeds; Movemenets, TD, VSTP, TSR, SCHEDULE & Reference Data. By combining these feeds, Turbostar will produce and orchastrate the daily timetables across the entire network by keeping track of each train and it's progress.

### SCHEDULE
The SCHEDULE feed is an extract from Network Rail's Train Planning system, ITPS. It contains train scheudles, associations & timing points of location codes.

Each schedule has a UID which may contain variations of a schedule and it is available overnight as a JSON file in two forms: full and daily.

### Movements
The Movements feed is a real-time feed reporting on a trains movement along its journey.

### TD
The Train Describer feed provides low-level detail about the position of trains and their train reporting number through a network of berths. Usually, but not always, a berth is associated with a signal - but there are locations (such as terminal platforms at stations) where there may be more than one berth. From each berth, there are zero or more other berths which a train description may step in to.

### VSTP
The VSTP (Very Short Term Planning) feed provides train schedules which are due to run in the next 48 hours that aren't included in the SCHEDULE feed. 

### TSR
Temporary Speed Restriction (TSR) messages are sent once a week, on a Friday at 0600, and contain a snapshot of the TSRs published in the Weekly Operating Notice. 
