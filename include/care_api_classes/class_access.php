<?php
/**
* @package care_api
*/

/**
*/
require_once($root_path.'include/care_api_classes/class_core.php');
/**
* Class for access authentication routines.
* Extends the class "Core".
* Note this class should be instantiated only after a "$db" adodb  connector object has been established by an adodb instance
* @package care_api
*/
class Access extends Core {
	/**
	* Users table name
	* @var string
	*/
	var $tb_user='care_users';
	/**
	* Users table name
	* @var string
	*/
	var $tb_role='care_user_roles';	
        /**
	* Users table name
	* @var string
	*/
	var $tb_assign='care_personell_assignment';
        /**
	* Users table name
	* @var string
	*/
	var $tb_personell='care_personell';
	/**
	* Holder for user data in associative array
	* @var array
	*/
	var $user=array();
	/**
	* Holder for role data in associative array
	* @var array
	*/
	var $role=array();
	/**
	* Allowed areas in hieararchical order
	* @var array
	*/
	var $allowedareas=array();
	/**
	* Allowed department id
	* @var int
	*/
	var $dept_nr;
	/**
	* User's registration status.
	* FALSE = unknown.
	* TRUE = known.
	* @var boolean
	*/
	var $usr_status=FALSE;
	/**
	* Flags if the "all" permission type is permitted.
	* Default is TRUE.
	* @var boolean
	*/
	var $permit_type_all=TRUE;
	/**
	* Password status.
	* FALSE = wrong password.
	* TRUE = correct password.
	* @var boolean
	*/
	var $pw_status=FALSE;
	/**
	* The access permission status.
	* FALSE = locked.
	* TRUE = access allowed.
	* @var boolean
	*/
	var $lock_status=FALSE;
	/**
	* Internal buffer for the login id (username)
	*/
	var $login_id;
	/**
	* Constructor. If login and password are passed as parameters, the access data are immediately loaded.
	*
	* For example:
	*
	* <code>
	* $user =  & new Access('Smith','Cocapabana');
	* if( $user->isKnown() && $user->hasValidPassword && $user->isNotLocked()){
	* ...
	* }
	* </code>
	*
	* @param string Login name
	* @param string Password
	* @access public
	* @return boolean
	*/
	function Access($login='',$pw=''){
		$this->coretable=$this->tb_user;
		$this->login_id =$login;
		if(!empty($login)&&!empty($pw)){
			return $this->loadAccess($login,$pw);
		}
	}
	/**
	 * Loads the role data
	 *
	 * @param int $id the id of the selected role
	 * @return boolean
	 */
	function roleAccess($id){
		$this->coretable=$tb_role;
		return $this->loadRole($id);
	}	
	/**
	* Loads the user data and checks its access status. 
	* Use if login and password were not passed during construction OR if a new access data is to be loaded using the same object instance.
	*
	* For example:
	*
	* <code>
	* $user =  & new Access;
	* ....
	* $user->loadAccess('Smith','Cocapabana');
	* if( $user->isKnown() && $user->hasValidPassword && $user->isNotLocked()){
	* ...
	* }
	* </code>
	*
	* @param string Login name
	* @param string Password
	* @access public
	* @return boolean
	*/
	function loadAccess($login='',$pw=''){
		/**
		* @global ADODB-db-link
		*/
		global $db;
		# Reset all status
		$this->pw_status=FALSE;
		$this->lock_status=FALSE;
		if(empty($login)){
			if(!empty($this->login)) $login=$this->login;
				else return FALSE;
		}
		if(empty($pw)){
			if(!empty($this->pw)) $pw=$this->pw;
				else return FALSE;
		}
		$this->sql="SELECT name,login_id,password, permission, lockflag FROM $this->tb_user WHERE login_id ='".addslashes($login)."'";
		if ($result=$db->Execute($this->sql)) {
		    if ($this->rec_count=$result->RecordCount()) {
		       $this->user=$result->FetchRow();
			   $this->usr_status=TRUE; # User is known
			   if($this->user['password']==md5($pw)) $this->pw_status=TRUE; # Password is valid
			   if((int)$this->user['lockflag'])  $this->lock_status=TRUE; # Access is locked
			   return TRUE;
			}else{
				$usr_status=FALSE;
				return FALSE;
			}
		}else{
			$usr_status=FALSE;
			return FALSE;
		}
	}
	/**
	 * Loads the role Status
	 *
	 * @param int $id Role ID
	 * @return boolean
	 */
	function loadRole($id){
		/**
		* @global ADODB-db-link
		*/
		global $db;
		# Reset all status
		if(empty($id)){
			return FALSE;
		}
		$this->sql="SELECT role_name, id, permission FROM $this->tb_role WHERE id ='".$id."'";
		if ($result=$db->Execute($this->sql)) {
		    if ($this->rec_count=$result->RecordCount()) {
		       $this->role=$result->FetchRow();
			   return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	/**
	* Returns the password status of the user
	* @access public
	* @return boolean  TRUE = password valid, else FALSE = invalid password
	*/
	function hasValidPassword(){
		return $this->pw_status;
	}
	/**
	* Returns the user  status of the user whether he is registered user or not.
	* @access public
	* @return boolean  TRUE = is registered as user, else FALSE = invalid user
	*/
	function isKnown(){
		return $this->usr_status;
	}
	/**
	* Returns the user permission "is locked?" status.
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @return boolean TRUE = User permissionis locked, else FALSE = user unknown or unregisted
	*/
	function isLocked(){
		return $this->lock_status;
	}
	/**
	* Returns the permission "is not locked?" status. A negation of isLocked() method.
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @return boolean FALSE = User permission is locked, else TRUE = permission is locked
	*/
	function isNotLocked(){
		return !$this->lock_status;
	}
	/**
	* Returns the user's registered name.
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @return string
	*/
	function Name(){
		return $this->user['name'];
	}
	/**
	* Returns the user's login name ( login username ).
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @return string
	*/
	function LoginName(){
		return $this->user['login_id'];
	}
	/**
	* Returns the permission areas of the user. No interpretation is returned.
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @return string
	*/
	function PermissionAreas(){
		return $this->user['permission'];
	}
	/**
	* Returns the permission dept of the user. No interpretation is returned.
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @return string
	*/
	function PermittedDepartment(){
		return unserialize($this->user['dept_nr']);
	}	
	/**
	* Checks if the user is permitted in a given protected area.
	*
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @param string The area to be checked.
	* @return string
	*/
	function isPermitted($area=''){
		if(empty($area)) return false;
		return (stristr($this->user['permission'],$area));
	}
	/**
	* Sets the allowed hierarchical areas.
	*
	* @param array The allowed areas in hierarchy.
	* @access public
	* @return string
	*/
	function setAllowedAreas($areas=''){
		if($areas){
			$this->allowedareas=$areas;
			return TRUE;
		}else{
			return FALSE;
		}
	}
	/**
	* Checks if the user is permitted in the group of protected areas.
	*
	* This checks also whether the user is permitted in the area due to its role or position in the privilege hierarchy.
	* The group of areas must be set first with the "setAllowedAreas()" method.
	* Use only after the access data was loaded by the constructor or loadAccess() method.
	* @access public
	* @param string The area to be checked.
	* @return string
	*/
	function isPermittedInGroup($user_area=''){
		if(empty($user_area)){
			return FALSE;
		}else{
			if(ereg('System_Admin', $user_area)){  // if System_admin return true
	   			return TRUE;
			}elseif(in_array('no_allow_type_all', $this->allowedareas)){ // check if the type "all" is blocked, if so return false
	     			return FALSE;
			}elseif($this->permit_type_all && ereg('_a_0_all', $user_area)){ // if type "all" , return true
				return TRUE;
			}else{                                                                  // else scan the permission
				for($j=0;$j<sizeof($this->allowedareas);$j++){
					if(ereg($this->allowedareas[$j],$user_area)) return TRUE;
				}
			}
			return FALSE;           // otherwise the user has no access permission in the area, return false
		}
	}
	/**
	*  Checks the  data if user exists based on his username (login id)
	*
	* @public
	* @param string Username or login id
	* @return mixed adodb record or boolean FALSE
	*/
	function UserExists($login_id){
		global $db;
		if(!empty($login_id)) $this->login_id=$login_id;
			elseif(empty($this->login_id)) return FALSE;

		$this->sql="SELECT * FROM care_users WHERE login_id='".addslashes($this->login_id)."'";

		if ($this->res['_ud']=$db->Execute($this->sql)) {
			if ($this->res['_ud']->RecordCount()) {
				$this->user = $this->res['_ud']->FetchRow();
				$this->lock_status = $this->user['lockflag'];
				return TRUE;
			} else {
				$this->usr_status=FALSE;
				return false;
			}
		} else {
			$this->usr_status=FALSE;
			return false;
		}
	}
	/**
	*  Checks the  data if role exists based on his id
	*
	* @public
	* @param int role id
	* @return mixed adodb record or boolean FALSE
	*/
	function roleExists($id){
		global $db;
		if(empty($id)) return FALSE;

		$this->sql="SELECT * FROM care_user_roles WHERE id='".$id."'";

		if ($this->res['_ud']=$db->Execute($this->sql)) {
			if ($this->res['_ud']->RecordCount()) {
				$this->role = $this->res['_ud']->FetchRow();
				return TRUE;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	/**
	*  Checks the  data if role exists based on his name
	*
	* @public
	* @param int role name
	* @return mixed adodb record or boolean FALSE
	*/
	function roleExistsByName($roleName){
		global $db;
		if(empty($roleName)) return FALSE;

		$this->sql="SELECT * FROM care_user_roles WHERE role_name='".$roleName."'";
		if ($this->res['_ud']=$db->Execute($this->sql)) {
			if ($this->res['_ud']->RecordCount()) {
				$this->role = $this->res['_ud']->FetchRow();
				return TRUE;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	/**
	*  Changes the lock status of the user
	*
	* @private
	* @param boolean
	* @return boolean
	*/
	function _changelock($newlockflag=0){
		$this->sql="UPDATE $this->tb_user SET lockflag='$newlockflag' WHERE login_id='$this->login_id'";
		return $this->Transact($this->sql);
	}
	/**
	*  Locks access permission of the user
	*
	* @public
	* @return boolean
	*/
	function Lock(){
		return $this->_changelock(1);
	}
	/**
	*  UNlocks access permission of the user
	*
	* @public
	* @return boolean
	*/
	function UnLock(){
		return $this->_changelock(0);
	}
	/**
	*  Deletes the user if exists based on his username (login id)
	*
	* @public
	* @param string Username or login id
	* @return mixed adodb record or boolean FALSE
	*/
	function Delete($login_id){
		global $db;
		if(!empty($login_id)) $this->login_id=$login_id;
			elseif(empty($this->login_id)) return FALSE;

		$this->sql="DELETE FROM $this->tb_user  WHERE login_id='$this->login_id'";

		if ($this->Transact($this->sql)) {
			$this->user = NULL;
			$this->allowedareas = NULL;
			$this->usr_status=FALSE;
			$this->pw_status=FALSE;
			$this->lock_status=FALSE;
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/**
	*  Deletes the role if exists based on his role id
	*
	* @public
	* @param id role id=
	* @return mixed adodb record or boolean FALSE
	*/
	function roleDelete($id){
		global $db;
		if(empty($id)) return FALSE;

		$this->sql="DELETE FROM $this->tb_role  WHERE id=$id";
		if ($this->Transact($this->sql)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/**
	 * Loads all the active roles
	 *
	 * @return ADODB
	 */
	function loadAllRoles(){
		global $db;
		# Reset all status
		$this->sql="SELECT * FROM $this->tb_role ORDER BY role_name ASC";
		if ($result=$db->Execute($this->sql)) {
		    if ($this->rec_count=$result->RecordCount()) {
		       return $result->GetArray();
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
        
        function checkNameRole($user_role=''){
            global $db;
            $this->sql="SELECT usr.role_name,pna.location_nr,us.dept_nr, us.name, us.personell_nr
                        FROM $this->tb_role AS usr
                        LEFT JOIN $this->tb_user AS us ON usr.id=us.user_role
                        LEFT JOIN $this->tb_assign AS pna On us.personell_nr=pna.personell_nr
                        WHERE us.name='$user_role'";
//            echo $this->sql;
            if($result=$db->Execute($this->sql)){
                if($this->rec_count=$result->FetchRow()) {
                    return $this->rec_count;
                }else{
                    return FALSE;
                }
            }else{
                return FALSE;
            }
        }
        
        function getrolename($id=''){
            global  $db;
            $this->sql="SELECT role_name FROM care_user_roles WHERE id=$id";
            if($result=$db->Execute($this->sql)){
                if($this->rec_count=$result->Fetchrow()) {
                    return $this->rec_count;
                }else return FALSE;
            }else return FALSE;
        }
        
        function loadAccess_per($login='',$deptnr){
		/**
		* @global ADODB-db-link
		*/
		global $db;
		# Reset all status
		$this->pw_status=FALSE;
		$this->lock_status=FALSE;
		if(empty($login)){
			if(!empty($this->login)) $login=$this->login;
				else return FALSE;
		}
		$this->sql="SELECT name,login_id,password, permission, lockflag FROM $this->tb_user WHERE login_id ='".addslashes($login)."'";
		if ($result=$db->Execute($this->sql)) {
		    if ($this->rec_count=$result->RecordCount()) {
		       $this->user=$result->FetchRow();
			   return $this->user;
			}else{
				$usr_status=FALSE;
				return FALSE;
			}
		}else{
			$usr_status=FALSE;
			return FALSE;
		}
	}
	function getAllActiveUserlogin(){
		 global  $db;
            $this->sql="SELECT * FROM care_users";
            if($result=$db->Execute($this->sql)){
                if($this->rec_count=$result->Fetchrow()) {
                    return $result;
                }else return FALSE;
            }else return FALSE;
	}
	function UpdateUserPermit($login_id,$permission){
		 global  $db;
		 $sql="UPDATE care_users set permission='".$permission."' where login_id='$login_id'";
		 //echo $sql;
		 return $db->Execute($sql);
	}
	function saveNewLoginID($name,$loginid,$pass){
			global $db;
			if(empty($name)) return false;
			if(empty($loginid)) return false;
			if(empty($pass))	return false;
			$pass = md5($pass);
			$this->sql="select login_id countuser from care_users where login_id='$loginid'";
			//echo $this->sql;
			if($result=$db->Execute($this->sql)){
				$this->rec_count=$result->Fetchrow();
                if($this->rec_count>0) {
                    return -1;
                }else{
                	$this->sql="insert into care_users (login_id,`name`,`password`) values ('$loginid',(select concat(p.name_last,' ',p.name_first) from care_personell pn, care_person p where p.pid = pn.pid and pn.nr = '$name'),'$pass')";
                	if($db->Execute($this->sql)) $rs = 1; else $rs = false;
                	return $rs;
                }
            }else return FALSE;
		}
	function saveDeptSelect($deptnr,$user){
		global $db;
		$sql="select dept_nr from care_users where login_id='$user'";
		
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$currdeptnr = $rs['dept_nr'];
			$currdeptnr .= '['.$deptnr.']';
			$sql = "update care_users set dept_nr = '".$currdeptnr."' where login_id='$user'";
			return $db->Execute($sql);
		}
		else return false;
	}
	function getDeptSelect($user){
		global $db;
		$sql="select dept_nr from care_users where login_id='$user'";
		$currdeptnr = "";
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$currdeptnr = $rs['dept_nr'];
		}
		return $currdeptnr;
	}
	function saveDeptTitle($dtitle,$user,$LDvar){
		global $db;
		$sql="select dtitle from care_users where login_id='$user'";
		
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$currdtitle = $rs['dtitle'];
			if(preg_match('/'.$LDvar.'=+[a-z]+,/', $currdtitle)){
				$currdtitle = preg_replace('/'.$LDvar.'=+[a-z]+,/',$LDvar.'='.$dtitle.',',$currdtitle);
			}
			else{
				$currdtitle .=$LDvar.'='.$dtitle.',';
			}
			$sql = "update care_users set dtitle = '".$currdtitle."' where login_id='$user'";
			return $db->Execute($sql);
		}
		else return false;
	}
	function getDeptRole($user){
		global $db;
		$sql="select dtitle from care_users where login_id='$user'";
		$deptrole = "";
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$deptrole = $rs['dtitle'];
		}
		return $deptrole;
	}
	function getDeptNRfromLD($LDvar){
		global $db;
		$sql="select nr from care_department where LD_var='$LDvar'";
		$deptrole = "";
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$deptnr = $rs['nr'];
		}
		return $deptnr;
	}
	function updateRoleOfDeptFromTitle($strrole,$LDvar,$user){
		global $db;
		$sql="select `permission` from care_users where login_id='$user'";
		
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$permission = $rs['permission'];
			if(preg_match('/'.$LDvar.'=+[a-z0-9_|]+,/', $permission)){
				$permission = preg_replace('/'.$LDvar.'=+[a-z0-9_|]+,/',$LDvar.'='.$strrole.',',$permission);
			}
			else{
				$permission .=$LDvar.'='.$strrole.',';
			}
			$sql = "update care_users set permission = '".$permission."' where login_id='$user'";
			return $db->Execute($sql);
		}
		else return false;
	}
	function removedept($deptnr,$LDvar,$user){
		global $db;
		$sql="select `permission` from care_users where login_id='$user'";
		$rs = 0;
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$permission = $rs['permission'];

			if(preg_match('/'.$LDvar.'=+[a-z0-9_|]+,/', $permission)){
				$permission = preg_replace('/'.$LDvar.'=+[a-z0-9_|]+,/','',$permission);
			}

			$sql = "update care_users set permission = '".$permission."' where login_id='$user'";
			($db->Execute($sql));
		}

		$sql="select dtitle from care_users where login_id='$user'";
		
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$currdtitle = $rs['dtitle'];

			if(preg_match('/'.$LDvar.'=+[a-z]+,/', $currdtitle)){
				$currdtitle = preg_replace('/'.$LDvar.'=+[a-z]+,/','',$currdtitle);
			}

			$sql = "update care_users set dtitle = '".$currdtitle."' where login_id='$user'";
			($db->Execute($sql));
		}

		$sql="select dept_nr from care_users where login_id='$user'";
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$currdept = $rs['dept_nr'];

			$currdept = str_replace('['.$deptnr.']', '', $currdept);

			$sql = "update care_users set dept_nr = '".$currdept."' where login_id='$user'";
			($db->Execute($sql));
		}

	}
	function getuserDeptPermission($user){
		global $db;
		$permission = "";
		$sql="select `permission` from care_users where login_id='$user'";
		if($result=$db->Execute($sql)){
			$rs=$result->Fetchrow();
			$permission = $rs['permission'];
		}
		return $permission;
	}
		//add 2709 - cot
	function getDeptFromPersonellID($loginid){
	global $db;
	$sql="select a.location_nr from care_personell_assignment a, care_users u where u.personell_nr = a.personell_nr and u.login_id = '$loginid'";
	if($rs = $db->Execute($sql)){
		$row= $rs->Fetchrow();
		$deptnr = $row['location_nr'];
		return $deptnr;
	}
	else return 0;
	}
	//end 2709
	function getAllpersonell(){
		global $db;
		$sql="select concat(p.name_last,' ',p.name_first) pname, pn.nr pnid from care_personell pn, care_person p where p.pid = pn.pid order by p.name_first";
		if($rs = $db->Execute($sql)){
			return $rs;
		}
		else return null;
	}
	function updatepersonell($loginid,$personell_nr){
		global $db;
		$sql="update care_users set personell_nr = '$personell_nr' where login_id='$loginid'";
		//echo $sql;
		$rs =  $db->Execute($sql);
		return $rs;
	}
	//add July 03 - cot
	
	function updateLogin($user,$newpass){
		global $db;
		$sql="update care_users set `password` = '".md5($newpass)."' where login_id='$user'";
		//echo $sql;
		$rs =  $db->Execute($sql);
		return $rs;
	}
}
?>
