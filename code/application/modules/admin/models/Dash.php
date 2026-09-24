<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dash extends MY_Model
{
	/*public function calculate_totals($module, $con = array('is_delete'=>'N'))
	{
		$this->db->where($con);
                $this->db->from($module);
		return $this->db->count_all_results();
	}

        public function calculate_total_booking_remain(){
            $rs = $this->db->query("select count(*) total_booking_remain from rooms
            where is_delete = 'N' and is_active = 'Y' and (booking_status='Y' || booking_status='P') ")->row();
            return $rs->total_booking_remain;
        }

        public function calculate_total_reserved(){
            $rs = $this->db->query(" select count(*) total_reserved from booking
                where is_delete = 'N' and is_active = 'Y' and booking_status = 'S' ")->row();
            return $rs->total_reserved;
        }

        public function calculate_total_agents(){
            $rs = $this->db->query(" select count(*) total_agents from agents
                where is_delete = 'N'  ")->row();
            return $rs->total_agents;
        }

        public function calculate_total_agents_booking(){
            $rs = $this->db->query(" select count(*) total_agents_booking from agents_payment
                where is_delete = 'N' and is_active = 'Y' and payment_status = 'S' ")->row();
            return $rs->total_agents_booking;
        }*/
}