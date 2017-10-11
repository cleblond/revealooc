<!DOCTYPE html>
<!-- This file expects $myonly (1=show users questions);  -->
<html ng-app="myApp" ng-init="USERID='<?= $USER->id ?>'" lang="en">
<head>
    <meta charset="utf-8">
   <!--  <link href="css/bootstrap.min.css" rel="stylesheet"> -->
    <style type="text/css">
    ul>li, a{cursor: pointer;}
    </style>
    <link rel="stylesheet" href="css/eocustom.css" type="text/css">
</head>
<body>


<div ng-controller="questionsCrtl">
<div class="container-fluid">
<br/>
    <div class="row">
        <div class="col-md-2">PageSize:
            <select ng-model="entryLimit" class="form-control">
                <option>5</option>
                <option>10</option>
                <option>20</option>
                <option>50</option>
                <option>100</option>
            </select>
    <!--   <input name="catid" id="catid"  type="hidden" ng-model="catid" value="<?= $catid ?>"/> -->
       <input type="hidden" name="myonly" id="myonly"  ng-model="myonly" class="form-control" value="<?= $myonly ?>"/>
       <input type="hidden" name="userid" id="userid"  type="hidden" ng-model="userid" value="<?= $userid_toshow ?>" class="form-control"/>
        </div>
        <div class="col-md-3">Filter:
            <input type="text" ng-model="search" ng-change="filter()" placeholder="Tags and keywords" class="form-control" />
           <!-- <input type="text" ng-model="searchcat" ng-change="filter()" placeholder="Filter" class="form-control" />  -->
        </div>
        
        <!-- spiner  -->
        <div class="col-md-2" >
        <div>  
            <img id="mySpinner" src="images/spinner.gif" ng-show="loading" />
        </div>  
        
        </div>
        
        <div class="col-md-5">
            <h5>Filtered {{ filtered.length }} of {{ totalItems}} total activities</h5>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12" ng-show="filteredItems > 0">
            <table id="tab2" data-escape="true" class="table table-striped table-hover table-condensed table-responsive">
            <thead>
        <!--    <th>Question Id<a ng-click="sort_by('question_id');"><i class="glyphicon glyphicon-sort"></i></a></th> -->
            <th>Description&nbsp;<a ng-click="sort_by('description');"><i class="glyphicon glyphicon-sort"></i></a></th>
        <!--    <th># of Ques.&nbsp;<a ng-click="sort_by('q_count');"><i class="glyphicon glyphicon-sort"></i></a></th> -->
         <!--   <th>Navigation Mode&nbsp;<a ng-click="sort_by('mode');"><i class="glyphicon glyphicon-sort"></i></a></th> -->
            <th ng-if="myonly == '1'" ng-cloak>Shared&nbsp;<a ng-click="sort_by('updated_at');"><i class="glyphicon glyphicon-sort"></i></a></th>
            <th>Last Updated&nbsp;<a ng-click="sort_by('updated_at');"><i class="glyphicon glyphicon-sort"></i></a></th>
           
            <th colspan="5">Action</th>
            </thead>
            <tbody ng-cloak> 
                <tr ng-repeat="data in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" ng-cloak>
                  <!--  <td>{{data.question_id}}</td> -->
                    <td ng-bind-html="data.description"></td>
                   <!--  <td>{{data.q_count}}</td> -->
                   <!-- <td>{{data.mode}}</td> -->
                    
                    <td ng-if="myonly == '1'" ng-cloak>{{data.share == "1" | iif : "Yes" : "No"}}</td>
                    <td><small>{{data.updated_at}}</small></td>
                    <td><a href='showdeck.php?slidedeck_id={{data.id}}' title="Preview"><i class='fa fa-search' aria-hidden='true'></i></a></td>
               <!--     <td><a href='activityintro.php?activity_id={{data.activity_id}}' title='Attempt Activity'><i class='fa fa-search' aria-hidden='true'></i></a></td> -->
                    

                    <td ng-if="data.user_id == USERID"><a href='index.php?slidedeck_id={{data.id}}&action=assign' title="Assign This SlideDeck!"><i class='fa fa-check-square-o' aria-hidden='true'></i></a></td>

                    <td ng-if="data.user_id !== USERID"></td><td ng-if="data.user_id !== USERID"></td><td ng-if="data.user_id !== USERID"></td>
                    
                    
                    <td ng-if="data.user_id == USERID"><a href='editdeck.php?activity_id={{data.activity_id}}' title='Edit Slide Deck (If your allowed.)'><i class='fa fa-cog' aria-hidden='true'></i></a></td>
                    
                    <td><a href='myactivities.php?activity_id={{data.activity_id}}&action=copy' title="Duplicate/Copy"><i class='fa fa-clone' aria-hidden='true'></i></a></td>
                    
                    
                    <td ng-if="data.user_id == USERID"><a ng-click="confirmDelete($event)" class='deleteactivity' href='myactivities.php?action=delete&activity_id={{data.activity_id}}' title='Delete Activity'><i class='fa fa-times' ariwindow.location.href set tsugi_element_id_8126be49 height=912
tsugiscripts.js:52lti_frameResize delta 6 is too small, ignored
tsugiscripts.js:78 sending {"subject":"lti.frameResize","height":918,"ela-hidden='true'></i></a></td>
                 <!--   <td><a href="previewquestion.php?question_id={{data.question_id}}" target="_blank">Pre</a></td>
                    <td><a href="editquestion.php?question_id={{data.question_id}}">Edit</a></td> -->
                </tr>
            </tbody>
            </table>
        </div>
        <div class="col-md-12" ng-show="filteredItems == 0" ng-cloak>
            <div class="col-md-12">
                <h4>No activites found</h4>
            </div>
        </div>
        <div class="col-md-12" ng-show="filteredItems > 0">   
 
        <!--    <div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div> -->

<div pagination="" page="currentPage" max-size="10" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="«" next-text="»"></div>
            
            
        </div>
    </div>
</div>
</div>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/angular-sanitize.min.js"></script>
<script src="js/ui-bootstrap-tpls-0.10.0.min.js"></script>
<script src="app/mydecks_app.js"></script>         
    </body>
</html>
