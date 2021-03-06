<?php 

namespace App\Http\Controllers\blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Mail, Cache, Session;
use Guzzlehttp\Client;

use App\Post;

class PagesController extends Controller {

	public function getIndex() {
		$posts = Post::orderBy('created_at', 'desc')->limit(6)->get();
		return view('blog.pages.welcome')->withPosts($posts);
	}

	public function getDirectivos() {
		$first = 'Alex';
		$last = 'Curtis';

		$fullname = $first . " " . $last;
		$email = 'gabarriosb@gmail.com';
		$data = [];
		$data['email'] = $email;
		$data['fullname'] = $fullname;
	  
		return view('blog.pages.directivos')
					->with('data', $data);
	}

	public function getContact() {
  	
  	// return the view and pass in the post object
		return view('blog.pages.contact');
	}

	public function eventCalendar() {
		return view('blog.pages.eventCalendar');
	}

	public function postContact(Request $request) {
		$this->validate($request, [
			'email' => 'required|email',
			'subject' => 'min:3',
			'message' => 'min:10']);

		$data = array(
			'email' => $request->email,
			'subject' => $request->subject,
			'bodyMessage' => $request->message
			);

		$token = $request->input('g-recaptcha-response');

		if ($token) {
			$client = new \GuzzleHttp\Client();
			$response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
					'form_params' => array(
						'secret' => '6LfPuwsUAAAAAG_G6mjC7XYxl0aPlJwdBWSV6-GW',
						'response' => $token
						)
				]);
		
			$results = json_decode($response->getBody()->getContents());
			if ($results->success) {
				Session::flash('success', 'Su Email ha sido enviado!');
				Mail::send('emails.contact', $data, function($message) use ($data){
					$message->from($data['email']);
					$message->to('gabarriosb@gmail.com');
					$message->subject($data['subject']);
				});
				return redirect()->route('frontend');
			
			} else {
				Session::flash('error', 'you are probably a roobot!');
				return redirect()->route('frontend');
			}
		
		} else {
			return redirect('frontend');
		}
	} // end function

	public function reglamento() {
		return view('blog.pages.reglamento');
	}
}