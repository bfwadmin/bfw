<?php

namespace Ram\Request\V20150501;

/**
 * Request of ListEntitiesForPolicy
 *
 * @method string getPolicyType()
 * @method string getPolicyName()
 */
class ListEntitiesForPolicyRequest extends \RpcAcsRequest
{

    /**
     * @var string
     */
    protected $requestScheme = 'https';

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'Ram',
            '2015-05-01',
            'ListEntitiesForPolicy',
            'ram'
        );
    }

    /**
     * @param string $policyType
     *
     * @return $this
     */
    public function setPolicyType($policyType)
    {
        $this->requestParameters['PolicyType'] = $policyType;
        $this->queryParameters['PolicyType'] = $policyType;

        return $this;
    }

    /**
     * @param string $policyName
     *
     * @return $this
     */
    public function setPolicyName($policyName)
    {
        $this->requestParameters['PolicyName'] = $policyName;
        $this->queryParameters['PolicyName'] = $policyName;

        return $this;
    }
}
