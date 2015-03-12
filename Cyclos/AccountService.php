<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class AccountService extends Service {

    function __construct() {
        parent::__construct('accountService');
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountBalanceLimitsOverviewData()
     */
    public function getAccountBalanceLimitsOverviewData() {
        return $this->run('getAccountBalanceLimitsOverviewData', array());
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.accounts.AccountHistoriesOverviewData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountHistoriesOverviewData()
     */
    public function getAccountHistoriesOverviewData() {
        return $this->run('getAccountHistoriesOverviewData', array());
    }
    
    /**
     * @param account Java type: java.lang.Long     * @param rateVisibility Java type: org.cyclos.model.banking.rates.RateVisibility
     * @return Java type: org.cyclos.model.banking.accounts.AccountHistoryData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountHistoryData(java.lang.Long,%20org.cyclos.model.banking.rates.RateVisibility)
     */
    public function getAccountHistoryData($account, $rateVisibility) {
        return $this->run('getAccountHistoryData', array($account, $rateVisibility));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoryQuery
     * @return Java type: org.cyclos.model.banking.accounts.AccountHistoryStatusVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountHistoryStatus(org.cyclos.model.banking.accounts.AccountHistoryQuery)
     */
    public function getAccountHistoryStatus($params) {
        return $this->run('getAccountHistoryStatus', array($params));
    }
    
    /**
     * @param accountId Java type: java.lang.Long     * @param dateTime Java type: org.cyclos.model.utils.DateTime     * @param rateVisibility Java type: org.cyclos.model.banking.rates.RateVisibility
     * @return Java type: org.cyclos.model.banking.accounts.AccountStatusVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountStatus(java.lang.Long,%20org.cyclos.model.utils.DateTime,%20org.cyclos.model.banking.rates.RateVisibility)
     */
    public function getAccountStatus($accountId, $dateTime, $rateVisibility) {
        return $this->run('getAccountStatus', array($accountId, $dateTime, $rateVisibility));
    }
    
    /**
     * @param owner Java type: org.cyclos.model.banking.accounts.AccountOwner     * @param dateTime Java type: org.cyclos.model.utils.DateTime
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountsSummary(org.cyclos.model.banking.accounts.AccountOwner,%20org.cyclos.model.utils.DateTime)
     */
    public function getAccountsSummary($owner, $dateTime) {
        return $this->run('getAccountsSummary', array($owner, $dateTime));
    }
    
    /**
     * @param accountId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.accounts.UserAccountLimitData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getUserAccountLimitData(java.lang.Long)
     */
    public function getUserAccountLimitData($accountId) {
        return $this->run('getUserAccountLimitData', array($accountId));
    }
    
    /**
     * @param userLocator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getUserAccountsLimits(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getUserAccountsLimits($userLocator) {
        return $this->run('getUserAccountsLimits', array($userLocator));
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.accounts.UserWithBalanceSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#getUserWithBalanceSearchData()
     */
    public function getUserWithBalanceSearchData() {
        return $this->run('getUserWithBalanceSearchData', array());
    }
    
    /**

     * @return Java type: boolean
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#hasAccessibleAccounts()
     */
    public function hasAccessibleAccounts() {
        return $this->run('hasAccessibleAccounts', array());
    }
    
    /**
     * @param fromAccountTypeId Java type: java.lang.Long     * @param toAccountTypeId Java type: java.lang.Long
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#listTransferFiltersForAccountHistoriesOverview(java.lang.Long,%20java.lang.Long)
     */
    public function listTransferFiltersForAccountHistoriesOverview($fromAccountTypeId, $toAccountTypeId) {
        return $this->run('listTransferFiltersForAccountHistoriesOverview', array($fromAccountTypeId, $toAccountTypeId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.accounts.AccountVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoryQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#printAccountHistory(org.cyclos.model.banking.accounts.AccountHistoryQuery)
     */
    public function printAccountHistory($params) {
        return $this->run('printAccountHistory', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchAccountBalanceLimitsOverview(org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewQuery)
     */
    public function searchAccountBalanceLimitsOverview($params) {
        return $this->run('searchAccountBalanceLimitsOverview', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchAccountHistoriesOverview(org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery)
     */
    public function searchAccountHistoriesOverview($params) {
        return $this->run('searchAccountHistoriesOverview', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoryQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchAccountHistory(org.cyclos.model.banking.accounts.AccountHistoryQuery)
     */
    public function searchAccountHistory($params) {
        return $this->run('searchAccountHistory', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.UserWithBalanceQuery
     * @return Java type: org.cyclos.model.banking.accounts.UsersWithBalancesResult
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchUsersWithBalances(org.cyclos.model.banking.accounts.UserWithBalanceQuery)
     */
    public function searchUsersWithBalances($params) {
        return $this->run('searchUsersWithBalances', array($params));
    }
    
    /**
     * @param data Java type: org.cyclos.model.banking.accounts.AccountLimitDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/AccountService.html#setBalanceLimit(org.cyclos.model.banking.accounts.AccountLimitDTO)
     */
    public function setBalanceLimit($data) {
        $this->run('setBalanceLimit', array($data));
    }
    
}