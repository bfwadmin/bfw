<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace Smartag\Request\V20180313;

class CreateSAGLinkLevelHaRequest extends \RpcAcsRequest
{
	function  __construct()
	{
		parent::__construct("Smartag", "2018-03-13", "CreateSAGLinkLevelHa", "smartag", "openAPI");
		$this->setMethod("POST");
	}

	private  $resourceOwnerId;

	private  $backupLinkId;

	private  $resourceOwnerAccount;

	private  $haType;

	private  $ownerAccount;

	private  $mainLinkRegionId;

	private  $smartAGId;

	private  $ownerId;

	private  $mainLinkId;

	private  $backupLinkRegionId;

	public function getResourceOwnerId() {
		return $this->resourceOwnerId;
	}

	public function setResourceOwnerId($resourceOwnerId) {
		$this->resourceOwnerId = $resourceOwnerId;
		$this->queryParameters["ResourceOwnerId"]=$resourceOwnerId;
	}

	public function getBackupLinkId() {
		return $this->backupLinkId;
	}

	public function setBackupLinkId($backupLinkId) {
		$this->backupLinkId = $backupLinkId;
		$this->queryParameters["BackupLinkId"]=$backupLinkId;
	}

	public function getResourceOwnerAccount() {
		return $this->resourceOwnerAccount;
	}

	public function setResourceOwnerAccount($resourceOwnerAccount) {
		$this->resourceOwnerAccount = $resourceOwnerAccount;
		$this->queryParameters["ResourceOwnerAccount"]=$resourceOwnerAccount;
	}

	public function getHaType() {
		return $this->haType;
	}

	public function setHaType($haType) {
		$this->haType = $haType;
		$this->queryParameters["HaType"]=$haType;
	}

	public function getOwnerAccount() {
		return $this->ownerAccount;
	}

	public function setOwnerAccount($ownerAccount) {
		$this->ownerAccount = $ownerAccount;
		$this->queryParameters["OwnerAccount"]=$ownerAccount;
	}

	public function getMainLinkRegionId() {
		return $this->mainLinkRegionId;
	}

	public function setMainLinkRegionId($mainLinkRegionId) {
		$this->mainLinkRegionId = $mainLinkRegionId;
		$this->queryParameters["MainLinkRegionId"]=$mainLinkRegionId;
	}

	public function getSmartAGId() {
		return $this->smartAGId;
	}

	public function setSmartAGId($smartAGId) {
		$this->smartAGId = $smartAGId;
		$this->queryParameters["SmartAGId"]=$smartAGId;
	}

	public function getOwnerId() {
		return $this->ownerId;
	}

	public function setOwnerId($ownerId) {
		$this->ownerId = $ownerId;
		$this->queryParameters["OwnerId"]=$ownerId;
	}

	public function getMainLinkId() {
		return $this->mainLinkId;
	}

	public function setMainLinkId($mainLinkId) {
		$this->mainLinkId = $mainLinkId;
		$this->queryParameters["MainLinkId"]=$mainLinkId;
	}

	public function getBackupLinkRegionId() {
		return $this->backupLinkRegionId;
	}

	public function setBackupLinkRegionId($backupLinkRegionId) {
		$this->backupLinkRegionId = $backupLinkRegionId;
		$this->queryParameters["BackupLinkRegionId"]=$backupLinkRegionId;
	}
	
}