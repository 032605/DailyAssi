<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends BaseController
{
    public function home(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $this->flash->addMessage('info', 'Sample flash message');

        $this->view->render($response, 'home.phtml');
        return $response;
    }

	 public function da(Request $request, Response $response, $args)
    {
        $this->logger->info("main page action dispatched");

        $this->flash->addMessage('info', 'Sample flash message');

		$user_seq_num = $_SESSION['seq_num'];
		$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
		$stmt->execute(array($user_seq_num));
		$row = $stmt->fetch();
		$dev_id= $row['dev_id'];
    $stmt = $this->db->prepare("SELECT * from air_sensor where sensor_dev_id=? ORDER BY air_date DESC LIMIT 100");
    $stmt->execute(array($dev_id));
    $row2 = $stmt->fetch();
    $stmt = $this->db->prepare("SELECT * from heart_sensor where users_user_seq_num=? ORDER BY heart_date DESC LIMIT 100");
    $stmt->execute(array($user_seq_num));
    $row3 = $stmt->fetch();

    $temperature = explode(".",$row2['temperature']);
    $heart_rate = $row3['heart_rate'];








		 $this->view->render($response, 'index.phtml',['temperature'=>$temperature, 'row2'=>$row2, 'heart_rate'=>$heart_rate,'post'=>$_POST]);
        return $response;
    }

    public function viewPost(Request $request, Response $response, $args)
    {
        $this->logger->info("View post using Doctrine with Slim 3");

        $messages = $this->flash->getMessage('info');

        try {
            $post = $this->em->find('App\Model\Post', intval($args['id']));
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }

        $this->view->render($response, 'post.twig', ['post' => $post, 'flash' => $messages]);
        return $response;
    }
}
