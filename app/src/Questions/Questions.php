<?php

namespace Miax\Questions;

/**
 * A class to handle questions
 *
 */
class Questions extends \Miax\MVC\CDatabaseModel
{
    /**
     * Find and return ten latest questions.
     *
     * @return array
     */
    public function findQuestions($limit=20)
    {
        // Specify values to get
        $getValues = ['id', 'title', 'created', 'name', 'acronym', 'email'];

        // Get question from db
        $strGetValues = implode(", ", $getValues);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('type ="Q"')
                 ->orderby('created DESC')
                 ->limit($limit);

        $this->db->execute();
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchAll();

        // Add Gravatar hash to questions
        foreach (array_keys($data) as $no) {
            if (isset($data[$no]['email'])) {
                $data[$no]['hash'] = md5( strtolower( trim( $data[$no]['email'] ) ) );
                unset($data[$no]['email']);
            }
            $data[$no]['t'] = self::getTags($data[$no]['id']);
            $data[$no] = array_merge($data[$no], self::howMany("qNo", $data[$no]['id']));
        }

        return $data;
    }

    /**
     * Find and return questions with a specific tag.
     *
     * @return array
     */
    public function findTaggedQuestions($id)
    {
        // Specify values to get
        $getValues = ['id', 'title', 'created', 'name', 'acronym', 'email'];

        // Get question from db
        $strGetValues = implode(", ", $getValues);
        $this->db->select($strGetValues)
                 ->from('VTaggedInfo')
                 ->where('tagId = ?')
                 ->orderby('created DESC');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchAll();

        // Add Gravatar hash to questions
        foreach (array_keys($data) as $no) {
            if (isset($data[$no]['email'])) {
                $data[$no]['hash'] = md5( strtolower( trim( $data[$no]['email'] ) ) );
                unset($data[$no]['email']);
            }
            $data[$no]['t'] = self::getTags($data[$no]['id']);
            $data[$no] = array_merge($data[$no], self::howMany("qNo", $data[$no]['id']));
        }

        return $data;
    }

    /**
     * Find and return questions and answers by specific author.
     *
     * @param int @id Id of author.
     * @return array
     */
    public function findByAuthor($acronym)
    {
        // Specify values to get
        $getValues['q'] = ['id', 'title', 'created'];
        $getValues['a'] = ['id', 'qNo', 'created'];

        // Get questions from db
        $strGetValues = implode(", ", $getValues['q']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('acronym = ? AND type = "Q"');

        $this->db->execute([$acronym]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data['q'] = $this->db->fetchAll();

        // Add tags and statistics to questions
        foreach (array_keys($data['q']) as $no) {
            $data['q'][$no]['t'] = self::getTags($data['q'][$no]['id']);
            $data['q'][$no] = array_merge($data['q'][$no], self::howMany("qNo", $data['q'][$no]['id']));
        }

        // Get answers from db
        $strGetValues = implode(", ", $getValues['a']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('acronym = ? AND type = "A"');

        $this->db->execute([$acronym]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data['a'] = $this->db->fetchAll();

        // Add title of question, tags and statistics to answers
        foreach (array_keys($data['a']) as $no) {
            $data['a'][$no]['title'] = self::getTitle($data['a'][$no]['qNo']);
            $data['a'][$no]['t'] = self::getTags($data['a'][$no]['qNo']);
            $data['a'][$no] = array_merge($data['a'][$no], self::howMany("qNo", $data['a'][$no]['qNo']));
        }

        // Include stylesheet for questions in theme
        $this->theme->addStylesheet('css/questions.css');

        return $data;
    }

    /**
     * Find and return a specific question with answers and comments.
     *
     * @param int $id get value of id from specific row
     * @return array
     */
    public function viewQuestion($id)
    {
        // Specify values to get
        $getValues['q'] = ['id', 'authorId', 'title', 'content', 'created', 'name', 'acronym', 'email'];
        $getValues['a'] = ['id', 'authorId', 'content', 'created', 'name', 'acronym', 'email'];
        $getValues['c'] = ['id', 'commentTo', 'authorId', 'content', 'created', 'name', 'acronym', 'email'];

        // Get question from db
        $strGetValues = implode(", ", $getValues['q']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('id = ?');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data['q'] = $this->db->fetchOne();

        // Add Gravatar hash to question
        if (isset($data['q']['email'])) {
            $data['q']['hash'] = md5( strtolower( trim( $data['q']['email'] ) ) );
            unset($data['q']['email']);
        }

        // Get tags for question
        $data['t'] = self::getTags($id);

        // Get answers from db
        $strGetValues = implode(", ", $getValues['a']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('qNo = ? AND type = "A"');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data['a'] = $this->db->fetchAll();

        // Add Gravatar hash to answers
        foreach (array_keys($data['a']) as $no) {
            if (isset($data['a'][$no]['email'])) {
                $data['a'][$no]['hash'] = md5( strtolower( trim( $data['a'][$no]['email'] ) ) );
                unset($data['a'][$no]['email']);
            }
        }

        // Get comment from db
        $strGetValues = implode(", ", $getValues['c']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('qNo = ? AND type = "C"');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $comments = $this->db->fetchAll();

        // Add Gravatar hash to comments
        foreach (array_keys($comments) as $no) {
            if (isset($comments[$no]['email'])) {
                $comments[$no]['hash'] = md5( strtolower( trim( $comments[$no]['email'] ) ) );
                unset($comments[$no]['email']);
            }
        }

        // Re-order and merge comments to $data, all comments for a specific question/answer in one subarray
        $data['c'] = [];
        foreach ($comments as $comment) {
            $data['c'][$comment['commentTo']][] = $comment;
        }

        return $data;
    }

    /**
     * Get the id of a question (qNo) from its answers or comments.
     *
     * @param int $id The ID for a specific content.
     * @return string
     */
    public function getqNo($id)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->where('id = ?');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchOne();

        return $data['qNo'];
    }

    /**
     * Get the title for a question.
     *
     * @param int $id The ID for a specific question.
     * @return string
     */
    public function getTitle($id)
    {
        $content = $this->findID($id);
        $info = $content->getProperties();
        return $info['title'];
    }

    /**
     * Get the content for a question.
     *
     * @param int $id The ID for a specific question.
     * @return string
     */
    public function getContent($id)
    {
        $content = $this->findID($id);
        $info = $content->getProperties();
        return $info['content'];
    }

    /**
     * Get tags for a question.
     *
     * @param int $id The ID for a specific question.
     * @return array
     */
    public function getTags($id=null)
    {
        if (is_numeric($id)) {
            $this->db->select('tagId, tag')
                     ->from('VTagged')
                     ->where('qNo = ?');

            $this->db->execute([$id]);
            $this->db->setFetchMode(\PDO::FETCH_KEY_PAIR);
            $data = $this->db->fetchAll();
        }
        elseif ($id === "popular") {
            $this->db->select('tagId, tag, COUNT(tagId) AS amount')
                     ->from('VTagged')
                     ->groupBy('tagId')
                     ->orderBy('amount DESC');

            $this->db->execute();
            $this->db->setFetchMode(\PDO::FETCH_ASSOC);
            $data = $this->db->fetchAll();
        }
        else {
            $this->db->select()
                     ->from('tags');

            $this->db->execute();
            $this->db->setFetchMode(\PDO::FETCH_KEY_PAIR);
            $data = $this->db->fetchAll();
        }
        return $data;
    }

    /**
     * Count how many questions (Q), answers (A) or comments (C) for a specific query (like question or authorId).
     *
     * @param string $col Defines what we are looking for, like text-id/authorId.
     * @param int $id ID of the specific question/author.
     * @return array
     */
    public function howMany($col, $id)
    {
        $this->db->select('type, COUNT(type)')
                 ->from('VInfo') //$this->getSource()
                 ->where($col.' = ?')
                 ->groupBy('type');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_KEY_PAIR);
        return $this->db->fetchAll();
    }

    /**
     * Get the name of a tag.
     *
     * @param int $id The ID for a specific tag.
     * @return string
     */
    public function getTagName($id)
    {
        // Get tag from db
        $this->db->select()
                 ->from('tags')
                 ->where('id = ?');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchOne();

        return $data['tag'];
    }

    /**
     * Get the id of a tag.
     *
     * @param string $tag The name for a specific tag.
     * @return int
     */
    public function getTagId($tag)
    {
        // Get tag from db
        $this->db->select()
                 ->from('tags')
                 ->where('tag = ?');

        $this->db->execute([$tag]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchOne();

        return $data['id'];
    }

    /**
     * Save text
     *
     * @param array $values key/values to save or empty to use object properties.
     * @return boolean true or false if saving went okey.
     */
    public function save($values = []) {
        // Lift out tags from values
        if ($values['type'] === "Q") {
            $tags = $values['tags'];
            unset($values['tags']);
        }

        // Set commentTo to NULL if empty
        if ($values['commentTo'] === "") {
            unset($values['commentTo']);
        }

        // Save text and get id
        $ret = parent::save($values);
        $id = $this->id;

        // Update with qNo if not exist
        if (empty($values['qNo'])) {
            $this->update([
                'id' => $id,
                'qNo' => $id
            ]);
        }

        // Save tags
        if ($values['type'] === "Q") {
            $ret = $this->saveTags($id, $tags);
        }

        return $ret;
    }


    /**
     * Create new row.
     *
     * @param array $values key/values to save.
     * @return boolean true or false if saving went okey.
     */
    public function saveTags($id, $tags)
    {
        // Prepare tags in array
        foreach ($tags as $tag) {
            $values[] = [$id, $this->getTagId($tag)];
        }

        // Delete old tags
        $this->db->delete(
            'questiontaglink',
            'qNo = ?'
        );
        $ret = $this->db->execute([$id]);

        // Add new tags
        $this->db->insert(
            'questiontaglink',
            ['qNo', 'tagId']
        );
        foreach ($values as $val) {
            $ret = $this->db->execute($val);
        }

        return $ret;
    }

}
