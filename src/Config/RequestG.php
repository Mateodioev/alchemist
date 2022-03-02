<?php 

use GuzzleHttp\Client;

/**
 * Request using Guzzle
 * @method SendReq Send a request to a url
 */
class RequestG {
  
  private $client;

  public $query;
  public $request;
  public $response;

  /**
   * Create Guzzle Client
   */
  public function __construct($config = [])
  {
    if (isset($config['query'])) {
      $this->query = $config['query'];
      unset($config['query']);
    }

    $this->client = new Client($config);
  }

  /**
   * Emulate Guzzle\Client 5 defaults query
   */
  public function Query(array $def = []):array
  {
    if ($this->query === null) {
      $this->query = $def;
    }
    $this->query = array_merge($this->query, $def);
    return $this->query;
  }

  public function SendReq(string $method, string $uri, array $params = [])
  {
    $this->request = $this->client->request($method, $uri, $params);
    $this->response = $this->request->getBody()->getContents();
    return $this->response;
  }
  

}