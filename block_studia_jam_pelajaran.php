<?php

class block_studia_jam_pelajaran extends block_base
{
    public function init()
    {
        $this->title = "Studia Jam Pelajaran";

    }

    public function get_jamlat()
    {
        global $USER;
        global $DB;
        $sql = "SELECT COUNT(1) / 2 * 100 percentage
                FROM jamlatsikka
                WHERE nip_pendek = :username";

        $jamlat = array(
            "zero" => true,
            "fifty" => false,
            "hundred" => false,
            "plus" => false
        );

        if(!isloggedin()) {
            return $jamlat;
        }

        $percentage =  $DB->get_record_sql($sql, array("username" => $USER->username))->percentage;


        if ($percentage == 0) {
            return $jamlat;
        } else {
            $jamlat["zero"] = false;

            if ($percentage >= 100) {
                $jamlat["hundred"] = true;
                if ($percentage > 100) {
                    $jamlat["plus"] = true;
                }
            } else {
                $jamlat["fifty"] = true;
            }
        }
 
        return $jamlat;
    }

    public function get_content()
    {
        global $OUTPUT;
        global $DB;

        $this->page->requires->css('/blocks/studia_jam_pelajaran/lib.css');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';
        $this->title = "Progress Jam Pelajaran";

        $data = $this->get_jamlat();
        $data['logo'] = (new moodle_url("/blocks/studia_jam_pelajaran/dist/svg/medal.svg"))->out();

        $this->content->text = $OUTPUT->render_from_template("block_studia_jam_pelajaran/index", $data);

        return $this->content;
    }

    public function applicable_formats()
    {
        return [
            'site-index' => true
        ];
    }

    public function instance_allow_multiple()
    {
        return true;
    }

    public function hide_header()
    {
        return true;
    }
}