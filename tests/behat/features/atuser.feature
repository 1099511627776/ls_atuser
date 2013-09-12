Feature: Greeting plugin standart features BDD
    Test base functionality of LiveStreet atuser plugin standart

    @mink:selenium2
    Scenario: atUser LiveStreet CMS
        #login
        Given I am on "/login"
            Then I wait "2000"
            Then I want to login as "admin"
            Then I am on "/topic/add"
            Then I fill in "topic_title" with "test topic1"
            Then I fill in "topic_text" with "test descripyion for topic @admin"
            Then I fill in "topic_tags" with "test topic"
            Then I press element by css "#submit_topic_publish"
            Then I wait "2000"
            Then the response should contain "<a class=\"ls-user\" href=\"http://livestreet.test/profile/admin/\">admin</a>"

        Given I am on "/topic/add"
            Then I fill in "topic_title" with "test topic1"
            Then I fill in "topic_text" with "test description for topic"
            Then I fill in "topic_tags" with "test topic"
            Then I press element by css "#submit_topic_publish"
            Then I wait "2000"
            Then I press element by css ".reply-header .link-dotted"
            Then I fill in "comment_text" with "This is a comment to @admin"
            Then I press element by css "#comment-button-submit"
            Then I wait "2000"
            Then the response should contain "<a class=\"ls-user\" href=\"http://livestreet.test/profile/admin/\">admin</a>"

        Given I am on "/topic/add"
            Then I fill in "topic_title" with "test topic2"
            Then I fill in "topic_text" with "test description for topic #tag"
            Then I fill in "topic_tags" with "test topic"
            Then I press element by css "#submit_topic_publish"
            Then I wait "2000"
            Then the response should contain "<a class=\"inline-tag\" href=\"http://livestreet.text/tag/\">tag</a>"

        Given I am on "/topic/add"
            Then I fill in "topic_title" with "test topic3"
            Then I fill in "topic_text" with "test description for topic"
            Then I fill in "topic_tags" with "test topic"
            Then I press element by css "#submit_topic_publish"
            Then I wait "2000"
            Then I press element by css ".reply-header .link-dotted"
            Then I fill in "comment_text" with "This is a comment with #tag"
            Then I press element by css "#comment-button-submit"
            Then I wait "2000"
            Then the response should contain "<a class=\"inline-tag\" href=\"http://livestreet.text/tag/\">tag</a>"
