<?php
namespace Nora\Util\Browser;

/**
 * User Agent Detactor
 */
class UserAgent
{
    private $_user_agent;

    static public function create ($user_agent)
    {
        return new UserAgent($user_agent);
    }

    public function __toString( )
    {
        return $this->_user_agent;
    }

    private function __construct($user_agent)
    {
        $this->_user_agent = $user_agent;
    }

    /**
     * ユーザエージェント
     */
    public function isDocomo( )
    {
        return false !== strpos($this->_user_agent, 'DoCoMo');
    }

    /**
     * ユーザエージェント
     */
    public function isAu( )
    {
        return false !== strpos($this->_user_agent, 'UP.Browser');
    }

    /**
     * ユーザエージェント
     */
    public function isSoftBank( )
    {
        return false !== strpos($this->_user_agent, 'SoftBank');
    }

    /**
     * ユーザエージェント
     */
    public function isWillcom( )
    {
        return false !== strpos($this->_user_agent, 'WILLCOM');
    }

    /**
     * ユーザエージェント
     */
    public function isEmobile( )
    {
        return false !== strpos($this->_user_agent, 'emobile');
    }

    /**
     * ユーザエージェント
     */
    public function isIPhone( )
    {
        return false !== strpos($this->_user_agent, 'iPhone');
    }

    /**
     * ユーザエージェント
     */
    public function isIPad( )
    {
        return false !== strpos($this->_user_agent, 'iPad');
    }

    /**
     * ユーザエージェント
     */
    public function isAndroid( )
    {
        return false !== strpos($this->_user_agent, 'Android');
    }

    /**
     * ユーザエージェント
     */
    public function isSp( )
    {
        return $this->isIphone() || ($this->isAndroid() && false !== strpos($this->_user_agent, 'Mobile'));
    }

    /**
     * ユーザエージェント
     */
    public function isTablet( )
    {
        return $this->isIpad() || ($this->isAndroid() && false === strpos($this->_user_agent, 'Mobile'));
    }

    /**
     * ユーザエージェント
     */
    public function isMobile( )
    {
        return 
            ($this->isdocomo() || $this->isau() || $this->issoftbank() || $this->iswillcom() || $this->isemobile())
            && !$this->issp() && !$this->istablet();
    }

    /**
     * ユーザエージェント
     */
    public function isPC( )
    {
        return !$this->ismobile() && !$this->istablet() && !$this->issp();
    }
}
