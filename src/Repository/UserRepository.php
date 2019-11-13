<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository
{
    const URL = "localhost:8080/api";

    public function getUsers()
    {
        $json = $this->callApi('GET', "/users");
        $data = json_decode($json, true);
        $users = array_map(function($user){ return $this->hydrate($user); }, $data);

        return $users;
    }

    public function getUserById($id)
    {
        $param = "/users/" . $id;
        $json = $this->callApi('GET', $param);
        $user = $this->hydrate($json);

        return $user;
    }

    public function getUserbyEmail($email)
    {
        $param = "/users/" . $email;
        $json = $this->callApi('GET', $param);
        $user = $this->hydrate($json);

        return $user;
    }

    public function addUser($data)
    {
        $json = $this->callApi('POST', "/users", $data);
        $user = $this->hydrate($json);

        return $user;
    }

    public function deleteUserById($id)
    {
        $param = "/users/" . $id;
        $this->callApi('DELETE', $param);
    }

    public function deleteUserByEmail($email)
    {
        $param = "/users/" . $email;
        $this->callApi('DELETE', $param);
    }

    public function modifyUserById($id, $data)
    {
        $param = "/users/" . $id;
        $json = $this->callApi('PUT', $param, $data);
        $user = $this->hydrate($json);

        return $user;
    }

    public function modifyUserByEmail($email, $data)
    {
        $param = "/users/" . $email;
        $json = $this->callApi('PUT', $param, $data);
        $user = $this->hydrate($json);

        return $user;
    }

    private function hydrate($json)
    {
        // TODO: find another way to handle the getUsers array send
        if (is_string($json)) {
            $data = json_decode($json, true);
        } else {
            $data = $json;
        }

        if ($data && !isset($data['error'])) {
            $user = new User();
            $user
                ->setId($data['id'])
                ->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setEmail($data['email'])
                ->setRoles($data['roles'])
                ->setPassword($data['password'])
            ;

            return $user;
        } else {
            return null;
        }
    }

    private function callApi($method, $param, $data=null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::URL . "$param");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);

        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($curl);
        if(curl_errno($curl)) {
            echo "ERREUR curl_exec : ".curl_error($curl);
        }
        curl_close($curl);

        return $response;
    }
}