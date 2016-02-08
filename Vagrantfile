Vagrant.configure(2) do |config|
    config.vm.box = "ncaro/php7-debian8-apache-nginx-mysql"
    config.vm.hostname = "ansiployer"

    config.vm.network "forwarded_port", guest: 3000, host: 3000
#    config.vm.network "forwarded_port", guest: 80, host: 8888
#    config.vm.network "forwarded_port", guest: 443, host: 4433

    config.vm.provision "shell", inline: <<-SCRIPT
        sudo apt-get update
        sudo apt-get install python-pip python-dev -y
        sudo pip install ansible==1.9.4
        ansible-galaxy install -r /vagrant/vagrant_config/requirements.yml --ignore-errors
    SCRIPT
    config.vm.provision "ansible_local" do  |ansible|
	    ansible.playbook 	    = "/vagrant/vagrant_config/playbook.yml"
	    ansible.install 		= true
    end
end
