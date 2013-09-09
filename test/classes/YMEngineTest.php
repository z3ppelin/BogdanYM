<?php
/**
 * Unit test for \bogdanym\YMEngine class.
 * 
 * @author      Bogdan Constantinescu <bog_con@yahoo.com>
 * @since       2013.08.01
 * @version     1.0
 * @link        GitHub  https://github.com/z3ppelin/BogdanYM.git
 * @licence     The MIT License (http://opensource.org/licenses/MIT); see LICENCE.txt
 */
namespace bogdanym;

class YMEngineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test username.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    Invalid param username. Must be a string.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorParamUsername1()
    {
        $objYM = new YMEngine(array(), '');
    }
    
    
    
    /**
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    Invalid param username. Must contain at most one @.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorParamUsername2()
    {
        $objYM = new YMEngine('john@doe@yahoo.com', '');
    }
    
    
    
    /**
     * Test username.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    Invalid param username. ID must match [a-z0-9_.+] and must have at most 32 chars.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorParamUsername3()
    {
        $strName = '';
        for ($intI = 0; $intI < 33; $intI++) {
            $strName .= 'a';
        }
        $objYM = new YMEngine($strName, '');
    }
    
    
    
    /**
     * Test username.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    Invalid param username. ID must match [a-z0-9_.+] and must have at most 32 chars.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorParamUsername4()
    {
        $objYM = new YMEngine('#johndoe', '');
    }
    
    
    
    /**
     * Test username.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    Invalid param username. DNS must match [a-z0-9_.+] and must have at most 64 chars.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorParamUsername5()
    {
        $strDns = '';
        for ($intI = 0; $intI < 62; $intI++) {
            $strDns .= 'a';
        }
        $objYM = new YMEngine('johndoe@' . $strDns . '.com', '');
    }
    
    
    
    /**
     * Test password.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    Invalid param password.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorParamPassword1()
    {
        $objYM = new YMEngine('johndoe', array());
    }
    
    
    
    /**
     * Test password.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    Invalid param password.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorParamPassword2()
    {
        $strPwd = '';
        for ($intI = 0; $intI < 33; $intI++) {
            $strPwd .= 'a';
        }
        $objYM = new YMEngine('johndoe', $strPwd);
    }
    
    
    
    /**
     * @requires extension curl
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructorCurl()
    {
        $objYM = new YMEngine('johndoe', 'abcdefgppppp');
    }
  
    
    
    /**
     * Test everything goes ok with some valid params.
     * @covers \bogdanym\YMEngine::__construct
     */
    public function testConstructor()
    {
        $objYM = new YMEngine('johndoe', 'abcdefgppppp');
    }
    
    
    
    /**
     * Test setter/getter method.
     * @covers \bogdanym\YMEngine::setTokens
     * @covers \bogdanym\YMEngine::getTokens
     */
    public function testSetGetTokens()
    {
        $arrTokens = array(
            'request' => 'someTestRequestToken',
            'access' => array(
                'oauth_token' => 'sometestOAuthToken',
                'oauth_token_secret' => 'someTestOAuthTokenSecret',
                'oauth_expires_in' => '3600',
                'oauth_session_handle' => 'someTestOAuthSessionHandle',
                'oauth_authorization_expires_in' => '770477963',
                'xoauth_yahoo_guid' => 'someTestXOAuthYahooGuid'
            ),
        );
        $objYM = new YMEngine('das1sdas', 'dasda123sdas');
        $objYM->setTokens($arrTokens);
        $this->assertSame($arrTokens, $objYM->getTokens());
        $this->assertTrue($objYM->hasAccessToken());
    }
    
    
    
    /**
     * Test setter/getter method.
     * @covers \bogdanym\YMEngine::setSession
     * @covers \bogdanym\YMEngine::getSession
     */
    public function testSetGetSession()
    {
        $arrSession = array(
            'sessionId' => 'someTestSessionId',
            'primaryLoginId' => 'someLoginId',
            'displayInfo' => array(
                'avatarPreference' => 0,
            ),
            'server' => 'rcore3.messenger.yahooapis.com',
            'notifyServer' => 'rproxy3.messenger.yahooapis.com',
            'constants' => array(
                'presenceSubscriptionsMaxPerRequest' => 500,
            ),
        );

        $objYM = new YMEngine('das1sdas', 'dasda123sdas');
        $objYM->setSession($arrSession);
        $this->assertSame($arrSession, $objYM->getSession());
    }
    
    
    
    /**
     * Test setter/getter method.
     * @covers \bogdanym\YMEngine::setTokenRenewed
     * @covers \bogdanym\YMEngine::isTokenRenewed
     */
    public function testSetIsTokenRenewed()
    {
        $objYM = new YMEngine('vxc123ads', 'das_+DAS');
        $objYM->setTokenRenewed(true);
        $this->assertSame(true, $objYM->isTokenRenewed());
        $objYM->setTokenRenewed(false);
        $this->assertSame(false, $objYM->isTokenRenewed());
        $objYM->setTokenRenewed(0);
        $this->assertSame(false, $objYM->isTokenRenewed());
        $objYM->setTokenRenewed('dasdasdas');
        $this->assertSame(true, $objYM->isTokenRenewed());
    }
    
    
    
    /**
     * Test exception is thrown when request token is not received from api call.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::fetchRequestToken
     */
    public function testFetchRequestTokenIsThrowingException()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('aaaaaaa'));
        $objStub->fetchRequestToken();
    }
    
    
    
    /**
     * Test method works properly when request token is received from api call.
     * @covers \bogdanym\YMEngine::fetchRequestToken
     */
    public function testFetchRequestTokenWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('RequestToken=aaaaaaa'));
        $objStub->fetchRequestToken();
    }
    
    
    
    /**
     * Test exception is thrown when tring to fetch access token and no request token was previously set.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    No request token previously set.
     * @covers \bogdanym\YMEngine::fetchAccessToken
     */
    public function testFetchAccessTokenIsThrowingExceptionWhenNoRequestTokenPreviouslySet()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->never())
                ->method('makeApiCall')
                ->will($this->returnValue('aaaaaaa'));
        $objStub->fetchAccessToken();
    }
    
    
    
    /**
     * Test exception is thrown when access token is not received from api call.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::fetchAccessToken
     */
    public function testFetchAccessTokenIsThrowingExceptionWhenNoAccessTokenIsReceived()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('aaaaaaa'));
        $objStub->setTokens(array('request' => 'testRequestToken'))
                ->fetchAccessToken();
    }
    
    
    
    /**
     * Test method is working properly where access token is received from api call.
     * @covers \bogdanym\YMEngine::fetchAccessToken
     */
    public function testFetchAccessTokenWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->setTokens(array('request' => 'testRequestToken'))
                ->fetchAccessToken();
    }
    
    
    
    /**
     * Test access token renewal is throwing exception when there is no access token to renew.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    No access token to renew.
     * @covers \bogdanym\YMEngine::fetchAccessToken
     */
    public function testFetchAccessTokenRenewalIsThrowingExceptionWhenNoAccessTokenPreviouslySet()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->never())
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->fetchAccessToken(true);
    }
    
    
    
    /**
     * Test access token renewal is throwing exception when no renewed access token is received from api call.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::fetchAccessToken
     */
    public function testFetchAccessTokenRenewalIsThrowingExceptionWhenNoNewAccessTokenIsReceived()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('dasdasdas'));
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_expires_in' => '3600',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                    'oauth_authorization_expires_in' => '770477963',
                    'xoauth_yahoo_guid' => 'someTestXOAuthYahooGuid'
                ),
            )
        )->fetchAccessToken(true);
    }
    
    
    
    /**
     * Test access token renewal is working fine.
     * @covers \bogdanym\YMEngine::fetchAccessToken
     */
    public function testFetchAccessTokenRenewalWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_expires_in' => '3600',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                    'oauth_authorization_expires_in' => '770477963',
                    'xoauth_yahoo_guid' => 'someTestXOAuthYahooGuid'
                ),
            )
        )->fetchAccessToken(true);
        $this->assertTrue($objStub->isTokenRenewed());
        $this->assertSame(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'testOAuthToken',
                    'oauth_token_secret' => 'testOAuthTokenSecret',
                    'oauth_expires_in' => '3600',
                    'oauth_session_handle' => 'testOAuthSessionHandle',
                    'oauth_authorization_expires_in' => '770477963',
                    'xoauth_yahoo_guid' => 'testXOAuthYahooGuid'
                ),
            ),
            $objStub->getTokens()
        );
    }
    
    
    
    /**
     * Test login throws exception if not access token is set.
     * @expectedException \bogdanym\YMException
     * @expectedExceptionMessage    No access token previously set.
     * @covers \bogdanym\YMEngine::logIn
     */
    public function testLogInThrowsExceptionWhenNoAccessToken()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->never())
                ->method('makeApiCall')
                ->will($this->returnValue('aaaa'));
        $objStub->logIn();
    }
    
    
    
    /**
     * Test login fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::logIn
     */
    public function testLogInThrowsExceptionWhenHttpStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('aaaa'));
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
    }
    
    
    
    /**
     * Test login fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::logIn
     */
    public function testLogInFailsWhenResponseIsNotValidJson()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return 'aaaa';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
    }
    
    
    
    /**
     * Test login works ok.
     * @covers \bogdanym\YMEngine::logIn
     */
    public function testLogInWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $arrSession = array(
            'sessionId' => 'someTestSessionId',
            'primaryLoginId' => 'someLoginId',
            'displayInfo' => array(
                'avatarPreference' => '0',
            ),
            'server' => 'rcore3.messenger.yahooapis.com',
            'notifyServer' => 'rproxy3.messenger.yahooapis.com',
            'constants' => array(
                'presenceSubscriptionsMaxPerRequest' => 500,
            ),
        );
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'displayInfo' => array(
                                        'avatarPreference' => '0',
                                    ),
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                    'notifyServer' => 'rproxy3.messenger.yahooapis.com',
                                    'constants' => array(
                                        'presenceSubscriptionsMaxPerRequest' => 500,
                                    ),
                                )
                            );
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
        $this->assertSame($arrSession, $objStub->getSession());
    }
    
    
    
    /**
     * Test logout works fine if previously not logged in; just do nothing
     * @covers \bogdanym\YMEngine::logOut
     */
    public function testLogOutWorksFineIfNotLoggedIn()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->never())
                ->method('makeApiCall')
                ->will($this->returnValue('dasdas'));
        $objStub->logOut();
    }
    
    
    
    /**
     * Test logout fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::logOut
     */
    public function testLogOutFailsWhenHttpStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $objStub->expects($this->once())
                ->method('makeApiCall')
                ->will($this->returnValue('dasdas'));
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->logOut();
    }
    
    
    
    /**
     * Test logout works fine if previously logged in.
     * @covers \bogdanym\YMEngine::logOut
     */
    public function testLogOutWorksFineIfLoggedIn()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->logOut();
    }
    
    
    
    /**
     * Test logout works fine if token expired.
     * @covers \bogdanym\YMEngine::logOut
     */
    public function testLogOutWorksFineIfLoggedInAndTokenExpired()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for logout to get access token expired
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'oauth_problem="token_expired"';
                        }
                    )
                );
        $objStub->expects($this->at(2)) // stubbing for logout to renew access token
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->expects($this->at(3)) // stubbing for logout to successfully logout
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->logOut();
        $this->assertTrue($objStub->isTokenRenewed());
    }
    
    
    
    /**
     * Test headers from HTTP response are parsed well.
     * @covers \bogdanym\YMEngine::getHeadersFromCurlResponse
     */
    public function testGetHeadersFromCurlResponse()
    {
        $objYM = new YMEngine('johndoe', 'abcdefgppppp');
        
        $class = new \ReflectionClass($objYM); // method is protected, use reflection to make it accessible
        $method = $class->getMethod('getHeadersFromCurlResponse');
        $method->setAccessible(true);
        
        $arrWithHeaders = array(
            'http_code' => 'HTTP/1.1 200 OK',
            'date' => 'Wed, 04 Sep 2013 08:48:31 GMT',
            'p3p' => 'policyref="http://info.yahoo.com/w3c/p3p.xml", CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC GOV"',
            'cache-control' => 'public,must-revalidate',
            'x-yahoo-msgr-imageurl' => 'http://msgr.zenfs.com/msgrDisImg/KMU47EN7G7XKKZJRK3EFJZSABQ',
            'connection' => 'close',
            'content-type' => '',
        );
        
        $this->assertSame(
            $method->invokeArgs(
                $objYM,
                array(
                    'HTTP/1.1 200 OK' . "\r\n"
                  . 'Date: Wed, 04 Sep 2013 08:48:31 GMT' . "\r\n"
                  . 'P3P: policyref="http://info.yahoo.com/w3c/p3p.xml", CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC GOV"' . "\r\n"
                  . 'cache-control: public,must-revalidate' . "\r\n"
                  . 'x-yahoo-msgr-imageurl: http://msgr.zenfs.com/msgrDisImg/KMU47EN7G7XKKZJRK3EFJZSABQ' . "\r\n"
                  . 'Connection: close' . "\r\n"
                  . 'Content-Type: ' . "\r\n" . "\r\n"
                  . 'bla bla some content'
                )
            ),
            $arrWithHeaders
        );
    }
    
    
    
    /**
     * Test user avatar retrieval works fine.
     * @covers \bogdanym\YMEngine::fetchCustomAvatar
     */
    public function testFetchCustomAvatarWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0)) // needed for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return 'HTTP/1.1 200 OK' . "\r\n"
                                 . 'Date: Wed, 04 Sep 2013 08:40:43 GMT' . "\r\n"
                                 . 'P3P: policyref="http://info.yahoo.com/w3c/p3p.xml", CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC GOV"' . "\r\n"
                                 . 'cache-control: public,must-revalidate' . "\r\n"
                                 . 'x-yahoo-msgr-imageurl: http://msgr.zenfs.com/msgrDisImg/KMU47EN7G7XKKZJRK3EFJZSABQ' . "\r\n"
                                 . 'Connection: close' . "\r\n"
                                 . 'Content-Type: ' . "\r\n" . "\r\n";
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
        $url = $objStub->fetchCustomAvatar('yahooid');
        $this->assertSame('http://msgr.zenfs.com/msgrDisImg/KMU47EN7G7XKKZJRK3EFJZSABQ', $url);
    }
    
    
    
    /**
     * Test user avatar retrieval fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::fetchCustomAvatar
     */
    public function testFetchCustomAvatarThrowsExceptionWhenStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0))  // needed for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'displayInfo' => array(
                                        'avatarPreference' => '0',
                                    ),
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                    'notifyServer' => 'rproxy3.messenger.yahooapis.com',
                                    'constants' => array(
                                        'presenceSubscriptionsMaxPerRequest' => 500,
                                    ),
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'bla bla bla';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->fetchCustomAvatar('yahooid');
        $this->fail("Exception should have been thrown");
    }
    
    
    
    /**
     * Test user avatar retrieval fails when header with the avatar url is not set
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::fetchCustomAvatar
     */
    public function testFetchCustomAvatarThrowsExceptionWhenNoAvatarIsReceived()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('ter34dgf', 'tert34gdh'));
        
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1))  // stubbing for fetchCustomAvatar
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return 'HTTP/1.1 200 OK' . "\r\n"
                                . 'Date: Wed, 04 Sep 2013 08:40:43 GMT' . "\r\n"
                                . 'P3P: policyref="http://info.yahoo.com/w3c/p3p.xml", CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC GOV"' . "\r\n"
                                . 'cache-control: public,must-revalidate' . "\r\n"
                                . 'Connection: close' . "\r\n"
                                . 'Content-Type: ' . "\r\n" . "\r\n";
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->fetchCustomAvatar('yahooid');
        $this->fail("Exception should have been thrown");
    }
    
    
    
    /**
     * Test user avatar retrieval works fine token is expired.
     * @covers \bogdanym\YMEngine::fetchCustomAvatar
     */
    public function testFetchCustomAvatarWorksFineIfTokenExpired()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('ter34dgf', 'tert34gdh'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for fetchCustomAvatar to get access token expired
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'oauth_problem="token_expired"';
                        }
                    )
                );
        $objStub->expects($this->at(2)) // stubbing for fetchCustomAvatar to renew access token
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->expects($this->at(3)) // stubbing for fetchCustomAvatar to successfully fetchCustomAvatar
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return 'HTTP/1.1 200 OK' . "\r\n"
                                . 'Date: Wed, 04 Sep 2013 08:40:43 GMT' . "\r\n"
                                . 'P3P: policyref="http://info.yahoo.com/w3c/p3p.xml", CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC GOV"' . "\r\n"
                                . 'cache-control: public,must-revalidate' . "\r\n"
                                . 'x-yahoo-msgr-imageurl: http://msgr.zenfs.com/msgrDisImg/KMU47EN7G7XKKZJRK3EFJZSABQ' . "\r\n"
                                . 'Connection: close' . "\r\n"
                                . 'Content-Type: ' . "\r\n" . "\r\n";
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
        $url = $objStub->fetchCustomAvatar('yahooid');
        $this->assertSame('http://msgr.zenfs.com/msgrDisImg/KMU47EN7G7XKKZJRK3EFJZSABQ', $url);
        $this->assertTrue($objStub->isTokenRenewed());
    }
    
    
    
    /**
     * Test groups retrieval works fine.
     * @covers \bogdanym\YMEngine::fetchGroups
     */
    public function testFetchGroupsWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('ter34dgf', 'tert34gdh'));
        
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
                
        $objStub->expects($this->at(1))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '{"groups":[{"group":{"name":"GroupX","uri":"rcore3.messenger.yahooapis.com\/v1\/group\/GroupX","contacts":[{"contact":{"id":"yahooid1","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid1","presence":{"presenceState":0},"clientCapabilities":[{"clientCapability":"richText"},{"clientCapability":"smiley"},{"clientCapability":"buzz"},{"clientCapability":"fileXfer"},{"clientCapability":"voice"},{"clientCapability":"interop"},{"clientCapability":"typing"}],"addressbook":{"id":"12","firstname":"Jonh","lastname":"Doe","lastModified":1376325172}}},{"contact":{"id":"yahooid2","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid2","presence":{"presenceState":-1},"clientCapabilities":[],"addressbook":{"id":"3","firstname":"Johnny","lastname":"Doe","lastModified":1192198013}}}]}}],"start":0,"total":1,"count":1}';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
        
        $groups = $objStub->fetchGroups();
        $this->assertTrue(is_array($groups));
        $this->assertSame($groups, json_decode('{"groups":[{"group":{"name":"GroupX","uri":"rcore3.messenger.yahooapis.com\/v1\/group\/GroupX","contacts":[{"contact":{"id":"yahooid1","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid1","presence":{"presenceState":0},"clientCapabilities":[{"clientCapability":"richText"},{"clientCapability":"smiley"},{"clientCapability":"buzz"},{"clientCapability":"fileXfer"},{"clientCapability":"voice"},{"clientCapability":"interop"},{"clientCapability":"typing"}],"addressbook":{"id":"12","firstname":"Jonh","lastname":"Doe","lastModified":1376325172}}},{"contact":{"id":"yahooid2","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid2","presence":{"presenceState":-1},"clientCapabilities":[],"addressbook":{"id":"3","firstname":"Johnny","lastname":"Doe","lastModified":1192198013}}}]}}],"start":0,"total":1,"count":1}', true));
    }
    
    
    
    /**
     * Test groups retrieval fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::fetchGroups
     */
    public function testFetchGroupsThrowsExceptionWhenStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('ter34dgf', 'tert34gdh'));
        
        $objStub->expects($this->at(0))  // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for fetchGroups
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                           $intHttpStatusCode = 401;
                           return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->fetchGroups();
        $this->fail("Exception should have been thrown");
    }
    
    
    
    /**
     * Test groups retrieval works fine if token expired.
     * @covers \bogdanym\YMEngine::fetchGroups
     */
    public function testFetchGroupsWorksFineIfTokenExpired()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for fetchGroups to get access token expired
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'oauth_problem="token_expired"';
                        }
                    )
                );
        $objStub->expects($this->at(2)) // stubbing for fetchGroups to renew access token
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->expects($this->at(3)) // stubbing for fetchGroups to successfully fetchGroups
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '{"groups":[{"group":{"name":"GroupX","uri":"rcore3.messenger.yahooapis.com\/v1\/group\/GroupX","contacts":[{"contact":{"id":"yahooid1","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid1","presence":{"presenceState":0},"clientCapabilities":[{"clientCapability":"richText"},{"clientCapability":"smiley"},{"clientCapability":"buzz"},{"clientCapability":"fileXfer"},{"clientCapability":"voice"},{"clientCapability":"interop"},{"clientCapability":"typing"}],"addressbook":{"id":"12","firstname":"Jonh","lastname":"Doe","lastModified":1376325172}}},{"contact":{"id":"yahooid2","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid2","presence":{"presenceState":-1},"clientCapabilities":[],"addressbook":{"id":"3","firstname":"Johnny","lastname":"Doe","lastModified":1192198013}}}]}}],"start":0,"total":1,"count":1}';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
        
        $groups = $objStub->fetchGroups();
        $this->assertTrue(is_array($groups));
        $this->assertSame($groups, json_decode('{"groups":[{"group":{"name":"GroupX","uri":"rcore3.messenger.yahooapis.com\/v1\/group\/GroupX","contacts":[{"contact":{"id":"yahooid1","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid1","presence":{"presenceState":0},"clientCapabilities":[{"clientCapability":"richText"},{"clientCapability":"smiley"},{"clientCapability":"buzz"},{"clientCapability":"fileXfer"},{"clientCapability":"voice"},{"clientCapability":"interop"},{"clientCapability":"typing"}],"addressbook":{"id":"12","firstname":"Jonh","lastname":"Doe","lastModified":1376325172}}},{"contact":{"id":"yahooid2","uri":"rcore3.messenger.yahooapis.com\/v1\/contact\/yahoo\/yahooid2","presence":{"presenceState":-1},"clientCapabilities":[],"addressbook":{"id":"3","firstname":"Johnny","lastname":"Doe","lastModified":1192198013}}}]}}],"start":0,"total":1,"count":1}', true));
        $this->assertTrue($objStub->isTokenRenewed());
    }
    
    
    
    /**
     * Test notifications retrieval works fine.
     * @covers \bogdanym\YMEngine::fetchNotifications
     */
    public function testFetchNotificationsWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
                
        $objStub->expects($this->at(1))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '{ "@pendingMsg" : 0, "@syncStatus" : 0, "responses" : [ { "message" : { "status" : 1, "sequence" : 4, "sender" : "yahooId1" , "receiver" : "myYahooId" , "msg" : "how are you?" , "timeStamp" : 1378303022, "hash" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ==" , "msgContext" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ=="  } } ] }';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
        
        $notifications = $objStub->fetchNotifications(4);
        $this->assertTrue(is_array($notifications));
        $this->assertSame($notifications, json_decode('{ "@pendingMsg" : 0, "@syncStatus" : 0, "responses" : [ { "message" : { "status" : 1, "sequence" : 4, "sender" : "yahooId1" , "receiver" : "myYahooId" , "msg" : "how are you?" , "timeStamp" : 1378303022, "hash" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ==" , "msgContext" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ=="  } } ] }', true));
    }
    
    
    
    /**
     * Test notifications retrieval fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::fetchNotifications
     */
    public function testFetchNotificationsThrowsExceptionWhenStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0))  // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for fetchNotifications
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                           $intHttpStatusCode = 401;
                           return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->fetchNotifications(4);
        $this->fail("Exception should have been thrown");
    }
    
    
    
    /**
     * Test notifications retrieval works fine if token expired.
     * @covers \bogdanym\YMEngine::fetchNotifications
     */
    public function testFetchNotificationsWorksFineIfTokenExpired()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for fetchGroups to get access token expired
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'oauth_problem="token_expired"';
                        }
                    )
                );
        $objStub->expects($this->at(2)) // stubbing for fetchGroups to renew access token
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->expects($this->at(3)) // stubbing for fetchGroups to successfully fetchGroups
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '{ "@pendingMsg" : 0, "@syncStatus" : 0, "responses" : [ { "message" : { "status" : 1, "sequence" : 4, "sender" : "yahooId1" , "receiver" : "myYahooId" , "msg" : "how are you?" , "timeStamp" : 1378303022, "hash" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ==" , "msgContext" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ=="  } } ] }';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn();
        
        $notifications = $objStub->fetchNotifications(4);
        $this->assertTrue(is_array($notifications));
        $this->assertSame($notifications, json_decode('{ "@pendingMsg" : 0, "@syncStatus" : 0, "responses" : [ { "message" : { "status" : 1, "sequence" : 4, "sender" : "yahooId1" , "receiver" : "myYahooId" , "msg" : "how are you?" , "timeStamp" : 1378303022, "hash" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ==" , "msgContext" : "QuyxE57kKbX4vp7K+OP1nTbfJ30hAQ=="  } } ] }', true));
        $this->assertTrue($objStub->isTokenRenewed());
    }
    
    
    
    /**
     * Test message sending works fine.
     * @covers \bogdanym\YMEngine::sendMessage
     */
    public function testSendMessageWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
                
        $objStub->expects($this->at(1))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->sendMessage('How are you my friend?', 'buddyYahooId');
    }
    
    
    
    /**
     * Test message sending fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::sendMessage
     */
    public function testSendMessageThrowsExceptionWhenStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0))  // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for sendMessage
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                           $intHttpStatusCode = 401;
                           return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->sendMessage('How are you my friend?', 'buddyYahooId');
        $this->fail("Exception should have been thrown");
    }
    
    
    
    /**
     * Test message sending works fine if token expired.
     * @covers \bogdanym\YMEngine::sendMessage
     */
    public function testSendMessageWorksFineIfTokenExpired()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for sendMessage to get access token expired
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'oauth_problem="token_expired"';
                        }
                    )
                );
        $objStub->expects($this->at(2)) // stubbing for sendMessage to renew access token
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->expects($this->at(3)) // stubbing for sendMessage to successfully sendMessage
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->sendMessage('How are you my friend?', 'buddyYahooId');
        $this->assertTrue($objStub->isTokenRenewed());
    }
    
    
    
    /**
     * Test presence state changing works fine.
     * @covers \bogdanym\YMEngine::changePresenceState
     */
    public function testChangePresenceStateWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
                
        $objStub->expects($this->at(1))
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->changePresenceState(\bogdanym\YMEngine::USER_IS_ONLINE, 'I \'m online :)');
    }
    
    
    
    /**
     * Test presence state changing fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::changePresenceState
     */
    public function testChangePresenceStateThrowsExceptionWhenStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0))  // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for sendMessage
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                           $intHttpStatusCode = 401;
                           return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->changePresenceState(\bogdanym\YMEngine::USER_IS_BUSY, 'Very very busy...');
        $this->fail("Exception should have been thrown");
    }
    
    
    
    /**
     * Test presence state changing works fine if token expired.
     * @covers \bogdanym\YMEngine::changePresenceState
     */
    public function testChangePresenceStateWorksFineIfTokenExpired()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for changePresenceState to get access token expired
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'oauth_problem="token_expired"';
                        }
                    )
                );
        $objStub->expects($this->at(2)) // stubbing for changePresenceState to renew access token
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->expects($this->at(3)) // stubbing for changePresenceState to successfully changePresenceState
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->changePresenceState(\bogdanym\YMEngine::USER_IS_BUSY, 'Very very busy...');
        $this->assertTrue($objStub->isTokenRenewed());
    }
    
    
    
    /**
     * Test buddy authorization works fine.
     * @covers \bogdanym\YMEngine::authorizeBuddy
     */
    public function testAuthorizeBuddyWorksFine()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
                
        $objStub->expects($this->at(1)) // stubbing for authorizeBuddy
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->authorizeBuddy('buddyYahooId', \bogdanym\YMEngine::BUDDY_ACCEPT);
    }
    
    
    
    /**
     * Test buddy authorization fails when http status code retreived from curl call is not 200.
     * @expectedException \bogdanym\YMException
     * @covers \bogdanym\YMEngine::authorizeBuddy
     */
    public function testAuthorizeBuddyThrowsExceptionWhenStatusIsNot200()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        
        $objStub->expects($this->at(0))  // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for authorizeBuddy
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                           $intHttpStatusCode = 401;
                           return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->authorizeBuddy('buddyYahooId', \bogdanym\YMEngine::BUDDY_ACCEPT);
        $this->fail("Exception should have been thrown");
    }
    
    
    
    /**
     * Test buddy authorization works fine if token expired.
     * @covers \bogdanym\YMEngine::authorizeBuddy
     */
    public function testAuthorizeBuddyWorksFineIfTokenExpired()
    {
        $objStub = $this->getMock('\bogdanym\YMEngine', array('makeApiCall'), array('usr', 'pass'));
        $intHttpStatusCode = 0;
        $objStub->expects($this->at(0)) // stubbing for login
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return json_encode(
                                array(
                                    'sessionId' => 'someTestSessionId',
                                    'primaryLoginId' => 'someLoginId',
                                    'server' => 'rcore3.messenger.yahooapis.com',
                                )
                            );
                        }
                    )
                );
        $objStub->expects($this->at(1)) // stubbing for authorizeBuddy to get access token expired
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 401;
                            return 'oauth_problem="token_expired"';
                        }
                    )
                );
        $objStub->expects($this->at(2)) // stubbing for authorizeBuddy to renew access token
                ->method('makeApiCall')
                ->will($this->returnValue('oauth_token=testOAuthToken&oauth_token_secret=testOAuthTokenSecret&oauth_expires_in=3600&oauth_session_handle=testOAuthSessionHandle&oauth_authorization_expires_in=770477963&xoauth_yahoo_guid=testXOAuthYahooGuid'));
        $objStub->expects($this->at(3)) // stubbing for authorizeBuddy to successfully authorizeBuddy
                ->method('makeApiCall')
                ->will(
                    $this->returnCallback(
                        function ($strUrl, $strMethod, $arrHeaders, $strPostData, $blnSuprimeResponseHeader, $intHttpStatusCode) {
                            $intHttpStatusCode = 200;
                            return '';
                        }
                    )
                );
        $objStub->setTokens(
            array(
                'request' => 'someTestRequestToken',
                'access' => array(
                    'oauth_token' => 'sometestOAuthToken',
                    'oauth_token_secret' => 'someTestOAuthTokenSecret',
                    'oauth_session_handle' => 'someTestOAuthSessionHandle',
                ),
            )
        )->logIn()
         ->authorizeBuddy('buddyYahooId', \bogdanym\YMEngine::BUDDY_ACCEPT);
        $this->assertTrue($objStub->isTokenRenewed());
    }
}
