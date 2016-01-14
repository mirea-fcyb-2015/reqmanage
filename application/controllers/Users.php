<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('form_validation'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	function index()
	{
		if (!$this->user->logged_in()) {
			redirect('users/login', 'refresh');
		}
		elseif (!$this->user->is_admin()) {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['projects'] = $this->user_projects;
			$this->data['users'] = $this->user->all_project_managers($this->user_projects);

			$this->template->set_title('Пользователи ваших проектов');
			$this->_render_page('auth/managers', $this->data);
		}
		else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['users'] = $this->user->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->user->get_users_groups($user->id)->result();
			}

			$this->template->set_title(lang('index_heading'));
			$this->_render_page('auth/index', $this->data);
		}
	}

	function login()
	{
		$this->data['title'] = "Логин";
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			$remember = (bool) $this->input->post('remember');

			if ($this->user->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				$this->session->set_flashdata('message', $this->user->messages());
				redirect('/', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('message', $this->user->errors());
				redirect('users/login', 'refresh');
			}
		}
		else
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);

			$this->template->set_title(lang('login_heading'));
			$this->_render_page('auth/login', $this->data);
		}
	}

	function logout()
	{
		$this->data['title'] = "Выход";
		$logout = $this->user->logout();
		$this->session->set_flashdata('message', $this->user->messages());
		redirect('', 'refresh');
	}

	function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->user->logged_in()) {
			redirect('users/login', 'refresh');
		}

		$user = $this->user->user()->row();

		if ($this->form_validation->run() == false) {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name'    => 'new',
				'id'      => 'new',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name'    => 'new_confirm',
				'id'      => 'new_confirm',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);
			$this->template->set_title(lang('change_password_heading'));
			$this->_render_page('auth/change_password', $this->data);
		}
		else {
			$identity = $this->session->userdata('identity');

			$change = $this->user->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change) {
				$this->session->set_flashdata('message', $this->user->messages());
				$this->logout();
			}
			else {
				$this->session->set_flashdata('message', $this->user->errors());
				redirect('users/change_password', 'refresh');
			}
		}
	}

	function forgot_password()
	{
		if($this->config->item('identity', 'ion_auth') != 'email') {
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else {
		   $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->template->set_title(lang('forgot_password_heading'));
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->user->where($identity_column, $this->input->post('email'))->users()->row();

			if(empty($identity)) {
        		if($this->config->item('identity', 'ion_auth') != 'email') {
            		$this->user->set_error('forgot_password_identity_not_found');
            	}
            	else {
            	   $this->user->set_error('forgot_password_email_not_found');
            	}

                $this->session->set_flashdata('message', $this->user->errors());
        		redirect("users/forgot_password", 'refresh');
            }

			$forgotten = $this->user->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				$this->session->set_flashdata('message', $this->user->messages());
				redirect("users/login", 'refresh');
			}
			else
			{
				$this->session->set_flashdata('message', $this->user->errors());
				redirect("users/forgot_password", 'refresh');
			}
		}
	}

	public function reset_password($code = NULL)
	{
		if (!$code) {
			show_404();
		}

		$user = $this->user->forgotten_password_check($code);

		if ($user) {
			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;
				$this->template->set_title(lang('reset_password_heading'));
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{
					$this->user->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->user->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						$this->session->set_flashdata('message', $this->user->messages());
						redirect("users/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->user->errors());
						redirect('users/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else {
			$this->session->set_flashdata('message', $this->user->errors());
			redirect("users/forgot_password", 'refresh');
		}
	}

	function activate($id, $code=false)
	{
		if ($code !== false) {
			$activation = $this->user->activate($id, $code);
		}
		else if ($this->user->is_admin()) {
			$activation = $this->user->activate($id);
		}

		if ($activation) {
			$this->session->set_flashdata('message', $this->user->messages());
			redirect("users", 'refresh');
		}
		else {
			$this->session->set_flashdata('message', $this->user->errors());
			redirect("users/forgot_password", 'refresh');
		}
	}

	function deactivate($id = NULL)
	{
		if (!$this->user->logged_in() || !$this->user->is_admin()) {
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->user->user($id)->row();

			$this->template->set_title(lang('deactivate_heading'));
			$this->_render_page('auth/deactivate_user', $this->data);
		}
		else {
			if ($this->input->post('confirm') == 'yes') {
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}
				if ($this->user->logged_in() && $this->user->is_admin()) {
					$this->user->deactivate($id);
				}
			}
			redirect('users', 'refresh');
		}
	}

	function create_user()
    {
        $this->data['title'] = "Create User";

        if (!$this->user->logged_in() || !$this->user->is_admin()) {
            redirect('users', 'refresh');
        }

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');

        if($identity_column!=='email') {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }

        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }

        if ($this->form_validation->run() == true && $this->user->register($identity, $password, $email, $additional_data)) {
            $this->session->set_flashdata('message', $this->user->messages());
            redirect("users", 'refresh');
        }
        else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->user->errors() ? $this->user->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity
                '),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

			$this->template->set_title(lang('create_user_heading'));
            $this->_render_page('auth/create_user', $this->data);
        }
    }

	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->user->logged_in() || (!$this->user->is_admin() && !($this->user->user()->row()->id == $id))) {
			redirect('users', 'refresh');
		}

		$user = $this->user->user($id)->row();
		$groups=$this->user->groups()->result_array();
		$currentGroups = $this->user->get_users_groups($id)->result();
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE) {
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
				);

				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}

				if ($this->user->is_admin()) {
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->user->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->user->add_to_group($grp, $id);
						}

					}
				}

			    if($this->user->update($user->id, $data)) {
				    $this->session->set_flashdata('message', $this->user->messages() );

				    if ($this->user->is_admin()) {
						redirect('users', 'refresh');
					}
					else {
						redirect('/', 'refresh');
					}

			    }
			    else {
				    $this->session->set_flashdata('message', $this->user->errors() );
				    if ($this->user->is_admin()) {
						redirect('users', 'refresh');
					}
					else {
						redirect('/', 'refresh');
					}
			    }
			}
		}

		$this->data['csrf'] = $this->_get_csrf_nonce();
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->user->errors() ? $this->user->errors() : $this->session->flashdata('message')));
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->template->set_title(lang('edit_user_heading'));
		$this->_render_page('auth/edit_user', $this->data);
	}

	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->user->logged_in() || !$this->user->is_admin()) {
			redirect('users', 'refresh');
		}
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE) {
			$new_group_id = $this->user->create_group($this->input->post('group_name'), $this->input->post('description'));

			if($new_group_id) {
				$this->session->set_flashdata('message', $this->user->messages());
				redirect("users", 'refresh');
			}
		}
		else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->user->errors() ? $this->user->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->template->set_title(lang('create_group_heading'));
			$this->_render_page('auth/create_group', $this->data);
		}
	}

	function edit_group($id)
	{
		if(!$id || empty($id)) {
			redirect('users', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->user->logged_in() || !$this->user->is_admin()) {
			redirect('users', 'refresh');
		}

		$group = $this->user->group($id)->row();
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$group_update = $this->user->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update) {
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else {
					$this->session->set_flashdata('message', $this->user->errors());
				}

				redirect("users", 'refresh');
			}
		}

		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->user->errors() ? $this->user->errors() : $this->session->flashdata('message')));
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->template->set_title(lang('edit_group_heading'));
		$this->_render_page('auth/edit_group', $this->data);
	}


	function register()
	{

		$this->data['title'] = 'Регистрация';

		if ($this->user->logged_in()) {
			redirect('/');
		}

		$tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');

        if($identity_column!=='email') {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }

        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }

        if ($this->form_validation->run() == true && $this->user->register($identity, $password, $email, $additional_data)) {
            $this->session->set_flashdata('message', $this->user->messages());
            redirect("users", 'refresh');
        }
        else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->user->errors() ? $this->user->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity
                '),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

			$this->template->set_title('Регистрация');
			$this->_render_page('auth/register', $this->data);
		}
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $returnhtml=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->template->load_view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;
	}

}
